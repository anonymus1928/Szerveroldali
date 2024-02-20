"use strict";

// Node.js projekt zippelő
// Készítette Tóta Dávid

const glob = require("glob");
const inquirer = require("inquirer");
const fs = require("fs").promises;
const { promisify } = require("util");
const chalk = require("chalk");
const date = require("date-and-time");
const Zip = require("adm-zip");
const path = require("path");
const slug = require("slug");
const filesize = require("filesize").partial({ base: 2, standard: "jedec" });
const _ = require("lodash");
const crypto = require("crypto");
const { table } = require("table");
const indentString = require("indent-string");
const treeify = require("object-treeify");
const stripAnsi = require("strip-ansi");
const { isBinaryFileSync } = require("isbinaryfile");
const config = require("./zip.config.js");

const pGlob = promisify(glob);
const currentDate = new Date();

const tableConfig = {
    border: {
        topBody: `─`,
        topJoin: `┬`,
        topLeft: `┌`,
        topRight: `┐`,

        bottomBody: `─`,
        bottomJoin: `┴`,
        bottomLeft: `└`,
        bottomRight: `┘`,

        bodyLeft: `│`,
        bodyRight: `│`,
        bodyJoin: `│`,

        joinBody: `─`,
        joinLeft: `├`,
        joinRight: `┤`,
        joinJoin: `┼`,
    },
};

// Nyilatkozat sablon
const statementTemplate = `NYILATKOZAT
===========
Én, {NAME} (Neptun kód: {NEPTUN}) kijelentem, hogy ezt a megoldást én küldtem be a {SUBJECT} tárgy {TASK} számonkéréséhez.

Kijelentem, hogy ez a megoldás a saját munkám. Nem másoltam vagy használtam harmadik féltől származó megoldásokat. Nem továbbítottam megoldást hallgatótársaimnak, és nem is tettem közzé. Nem használtam mesterséges intelligencia által generált kódot, kódrészletet. Az ELTE HKR 377/A. § értelmében, ha nem megengedett segédeszközt veszek igénybe, vagy más hallgatónak nem megengedett segítséget nyújtok, a tantárgyat nem teljesíthetem.

ELTE Hallgatói Követelményrendszer, IK kari különös rész, 377/A. §: "Az a hallgató, aki olyan tanulmányi teljesítménymérés (vizsga, zárthelyi, beadandó feladat) során, amelynek keretében számítógépes program vagy programmodul elkészítése a feladat, az oktató által meghatározottakon kívül más segédeszközt vesz igénybe, illetve más hallgatónak meg nem engedett segítséget nyújt, tanulmányi szabálytalanságot követ el, ezért az adott félévben a tantárgyat nem teljesítheti és a tantárgy kreditjét nem szerezheti meg."

Kelt: {DATE}
`;

// Reguláris kifejezés nevesített csoportokkal, amivel bekérhetők a nyilatkozatban lévő adatok
const statementRegex = new RegExp(
    // A kiindulási pont a nyilatkozat sablonja, csak pár dolgot át kell benne írni,
    // hogy működjön a mintaillesztés
    statementTemplate
        // Speciális karakterek escape-lése
        .replace(/[-[\]()*+?.,\\^$|#\s]/g, "\\$&")
        // Adatok behelyettesítése
        .replace("{NAME}", "(?<name>[^,]+)")
        .replace("{NEPTUN}", "(?<neptun>[0-9a-zA-Z]{6})")
        .replace("{SUBJECT}", '(?<subject>[^"]+)')
        .replace("{TASK}", '(?<task>[^"]+)')
        .replace("{DATE}", "(?<date>[^\n]+)"),
    "gm"
);

const getStatementData = async () => {
    let result = { name: null, neptun: null, exists: false, valid: false };
    try {
        const statementContent = (await fs.readFile("./statement.txt")).toString();
        const match = statementRegex.exec(statementContent);
        if (match && match.groups) {
            return _.merge({}, result, { exists: true, valid: true, ...match.groups });
        }
        return _.merge({}, result, { exists: true });
    } catch (e) {}
    return result;
};

const collectFiles = async () =>
    await pGlob("**", {
        ignore: config.ignore,
        dot: true,
        nodir: true,
    });

const checksum = (content) => {
    return crypto.createHash("md5").update(content, "utf8").digest("hex");
};

const zipFiles = async (files, { name, neptun }) => {
    process.stdout.write(" 2. Fájlok becsomagolása... ");
    const zip = new Zip();
    for (const file of files) {
        zip.addLocalFile(file, path.parse(file).dir);
    }
    console.log(chalk.green("OK."));
    const formattedDate = date.format(new Date(), "YYYYMMDD_HHmmss");
    const nameSlug = slug(name, { replacement: "_", lower: false });
    const task = slug(config.task, { replacement: "_" });
    const zipName = `${nameSlug}_${neptun}_${task}_${formattedDate}.zip`;
    const zipPath = `zipfiles/${zipName}`;
    process.stdout.write(" 3. Archívum mentése ide: " + chalk.gray(zipPath) + "... ");
    zip.writeZip(zipPath);
    const zipSize = filesize((await fs.stat(zipPath)).size);
    console.log(chalk.white(`${chalk.green("OK")}. Méret: ${chalk.gray(zipSize)}.`));
    return { zippedFiles: files, zipPath };
};

const checkZip = async (zippedFiles, zipPath) => {
    console.log(" Becsomagolt fájlok áttekintése:");
    const zipFile = new Zip(zipPath);
    let fileTree = {};
    let checksumMismatches = [];
    for (const file of zippedFiles) {
        const parsed = path.parse(file);
        const originalFileContentBuffer = await fs.readFile(file);
        const originalChecksum = checksum(originalFileContentBuffer);
        const zippedFileContentBuffer = zipFile.getEntry(file).getData();
        const zippedChecksum = checksum(zippedFileContentBuffer);
        const checksumMatch = originalChecksum === zippedChecksum;
        if (!checksumMatch)
            checksumMismatches.push([chalk.red(file), chalk.yellow(originalChecksum), chalk.yellow(zippedChecksum)]);
        const data = {
            dirs: parsed.dir
                .split("/")
                .filter((dir) => dir !== "")
                .map((dir) => chalk.yellow(dir)),
            file: checksumMatch ? chalk.green(parsed.base) : chalk.red(parsed.base),
            checksumMatch,
        };
        _.set(
            fileTree,
            [...data.dirs, data.file],
            chalk.gray(
                [
                    isBinaryFileSync(zippedFileContentBuffer)
                        ? "Bináris fájl"
                        : `${zippedFileContentBuffer.toString().split("\n").length} sor`,
                    `eredeti méret: ${filesize(Buffer.byteLength(zippedFileContentBuffer))}`,
                ].join(", ")
            )
        );
    }

    const order = (obj) => {
        obj = _.fromPairs(
            _.toPairs(obj).sort((a, b) => {
                if (_.isString(a[1]) && _.isObject(b[1])) return 1;
                else if (_.isObject(a[1]) && _.isString(b[1])) return -1;
                return stripAnsi(a[0]).localeCompare(stripAnsi(b[0]));
            })
        );
        for (const key of Object.keys(obj)) if (_.isObject(obj[key])) obj[key] = order(obj[key]);
        return obj;
    };

    fileTree = order(fileTree);

    console.log(indentString(chalk.yellow(zipPath), 4));
    console.log(indentString(treeify(fileTree), 4));
    console.log(" ");

    console.log(
        indentString(
            chalk.white(
                `* Ha egy fájl neve ${chalk.green(
                    "zöld"
                )}, az azt jelenti, hogy a fájl az ellenőrzés alapján sértetlenül be lett csomagolva a zip-be.`
            ),
            4
        )
    );
    console.log(
        indentString(
            "* A zippelő csak azt ellenőrizte, hogy a fájl sértetlenül lett-e becsomagolva, a tartalmát nem!",
            4
        )
    );

    if (checksumMismatches.length > 0) {
        console.log(" ");
        console.log(
            indentString(
                chalk.bgRed.white(
                    `${chalk.bold("FIGYELEM!")} Az alábbi fájlok az ellenőrzés során sérültnek bizonyultak:`
                ),
                4
            )
        );
        console.log(
            indentString(
                table(
                    [
                        ["Fájl", "Eredeti fájl ellenőrző összege", "Zippelt fájl ellenőrző összege"],
                        ...checksumMismatches,
                    ],
                    tableConfig
                ),
                4
            )
        );
        console.log(
            indentString(
                chalk.red(
                    "Elképzelhető, hogy a probléma megoldható azzal, ha bezárod azokat a programokat, amelyek használhatják a fájlokat."
                ),
                4
            )
        );
        console.log(
            indentString(
                chalk.red(
                    "Az is előfordulhat, hogy ez egy egyszeri, váratlan hiba volt, és a következő próbálkozásnál már minden jó lesz."
                ),
                4
            )
        );
    }
};

const handleStatement = async () => {
    // Korábbi kitöltés ellenőrzése és validálása
    let data = await getStatementData();

    if (data.exists) {
        if (data.valid) {
            console.log(
                chalk.green(
                    `>> A nyilatkozat (${chalk.yellow("statement.txt")}) korábban ki lett töltve ${chalk.yellow(
                        data.name
                    )} névre és ${chalk.yellow(data.neptun)} Neptun kódra.`
                )
            );
            console.log(
                chalk.green(
                    `   Ha a megadott adatok hibásak, töröld a ${chalk.yellow(
                        "statement.txt"
                    )} fájlt és hívd meg újra a zip parancsot a`
                )
            );
            console.log(chalk.green(`   nyilatkozat kitöltő újboli eléréséhez.`));

            console.log(" ");
            // Ha korábban ki lett töltve, itt végeztünk is
            return { name: data.name, neptun: data.neptun };
        } else {
            console.log(
                chalk.yellow(
                    `>> A nyilatkozat (${chalk.white(
                        "statement.txt"
                    )}) létezik, de úgy értékeltük, hogy a tartalma érvénytelen.`
                )
            );
            console.log(" ");
        }
    }

    // Nyilatkozat szövegének megjelenítése
    const statementTemplateReplaced = statementTemplate
        .replace("{SUBJECT}", config.subject)
        .replace("{TASK}", config.task)
        .replace("{DATE}", date.format(currentDate, "YYYY. MM. DD."));
    for (const line of statementTemplateReplaced.split("\n")) {
        console.log(chalk.gray(line));
    }
    console.log(" ");

    // Nyilatkozat elfogadása
    const { accepted } = await inquirer.prompt([
        {
            type: "list",
            name: "accepted",
            message: "Elfogadod a fenti nyilatkozatot?",
            choices: ["Igen", "Nem"],
            filter(answer) {
                return answer.toLowerCase();
            },
        },
    ]);

    if (accepted === "igen") {
        console.log(
            chalk.green(
                ">> Elfogadtad a nyilatkozatot. Kérjük, add meg az adataidat, hogy be tudjuk azokat helyettesíteni a nyilatkozatba."
            )
        );
    } else {
        console.log(
            chalk.bgRed.white(
                ">> A tárgy követelményei szerint a nyilatkozat elfogadása és mellékelése kötelező, ezért ha nem fogadod el, akkor nem tudjuk értékelni a zárthelyidet."
            )
        );
        throw new Error("StatementDenied");
    }

    // Adatok bekérése
    const questions = [
        {
            type: "input",
            name: "name",
            message: "Mi a neved?",
            validate(name) {
                name = name.trim();
                if (name.length < 2) {
                    return "A név legalább 2 karakter hosszú kell, hogy legyen!";
                }
                if (name.indexOf(" ") === -1) {
                    return "A név legalább 2 részből kell álljon, szóközzel elválasztva!";
                }
                return true;
            },
            filter(name) {
                return name
                    .split(" ")
                    .filter((part) => part.length > 0)
                    .map((part) => {
                        let partLower = part.toLowerCase();
                        return partLower.charAt(0).toUpperCase() + partLower.slice(1);
                    })
                    .join(" ");
            },
        },
        {
            type: "input",
            name: "neptun",
            message: "Mi a Neptun kódod?",
            validate(neptun) {
                neptun = neptun.trim();
                if (neptun.length !== 6) {
                    return "A Neptun kód hossza pontosan 6 karakter, hogy legyen!";
                }
                if (!neptun.match(new RegExp("[0-9A-Za-z]{6}"))) {
                    return "A Neptun kód csak számokból (0-9) és az angol ABC betűiből (A-Z) állhat!";
                }
                return true;
            },
            filter(neptun) {
                return neptun.toUpperCase();
            },
        },
    ];

    const { name, neptun } = await inquirer.prompt(questions);

    // Nyilatkozat kitöltése
    await fs.writeFile(
        "./statement.txt",
        statementTemplate
            .replace("{NAME}", name)
            .replace("{NEPTUN}", neptun)
            .replace("{SUBJECT}", config.subject)
            .replace("{TASK}", config.task)
            .replace("{DATE}", date.format(currentDate, "YYYY. MM. DD. HH:mm:ss"))
    );
    console.log(
        chalk.green(
            `>> A nyilatkozat (${chalk.yellow("statement.txt")}) sikeresen ki lett töltve ${chalk.yellow(
                name
            )} névre és ${chalk.yellow(neptun)} Neptun kódra.`
        )
    );
    console.log(" ");

    return { name, neptun };
};

const handleZipping = async ({ name, neptun }) => {
    // zipfiles mappa elkészítése, ha még nem létezik
    try {
        await fs.mkdir("zipfiles");
    } catch (e) {}

    // Fájlok listájának előállítása, majd az alapján becsomagolás
    process.stdout.write(" 1. Fájlok összegyűjtése... ");
    const files = await collectFiles();
    console.log(chalk.green("OK."));

    return await zipFiles(files, { name, neptun });
};

(async () => {
    try {
        console.log(chalk.bgGray.black("1. lépés: Nyilatkozat"));
        console.log(" ");
        const { name, neptun } = await handleStatement();

        console.log(chalk.bgGray.black("2. lépés: Becsomagolás"));
        console.log(" ");
        const { zippedFiles, zipPath } = await handleZipping({ name, neptun });

        console.log(" ");
        console.log(chalk.bgGray.black("3. lépés: Ellenőrzés"));
        console.log(" ");
        await checkZip(zippedFiles, zipPath);

        // Tudnivalók megjelenítése
        console.log(" ");
        console.log(chalk.bgGray.black("Tudnivalók"));
        console.log(" ");
        console.log(chalk.yellow(" * A feladatot a Canvas rendszeren keresztül kell beadni a határidő lejárta előtt."));
        console.log(chalk.yellow(" * A feladat megfelelő, hiánytalan beadása a hallgató felelőssége!"));
        console.log(
            chalk.yellow(" * Utólagos reklamációra nincs lehetőség! Mindenképp ellenőrizd a .zip fájlt, mielőtt beadod!")
        );
    } catch (e) {
        if (e.message === "StatementDenied") {
            return;
        }
        throw e;
    }
})();
