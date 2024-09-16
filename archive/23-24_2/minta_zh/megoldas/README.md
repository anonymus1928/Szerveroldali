# Szerveroldali webprogramozás Node.js ZH kezdőcsomag

Kezdőcsomag a Szerveroldali webprogramozás tárgy Node.js (Sequelize, REST API, GraphQL) zárthelyi feladatához.

- [Szerveroldali webprogramozás Node.js ZH kezdőcsomag](#szerveroldali-webprogramozás-nodejs-zh-kezdőcsomag)
  - [Függőségek telepítése](#függőségek-telepítése)
  - [Kezdőcsomag parancsok](#kezdőcsomag-parancsok)
  - [Ajánlott VSCode kiegészítők](#ajánlott-vscode-kiegészítők)
  - [Automatikus formázás mentéskor](#automatikus-formázás-mentéskor)
  - [Automatikus tesztelő, tudnivalók](#automatikus-tesztelő-tudnivalók)
    - [Tesztelő telepítése a zárthelyi elején](#tesztelő-telepítése-a-zárthelyi-elején)
    - [Fontos tudnivalók](#fontos-tudnivalók)
  - [Automatikus zippelő, beadás](#automatikus-zippelő-beadás)

## Függőségek telepítése

- Node.js, build eszközök telepítése: https://github.com/szerveroldali/leirasok/blob/main/Eszkozok.md#nodejs
- Csomagok telepítése: `npm install`

## Kezdőcsomag parancsok

Miután telepítetted az NPM-es csomagokat, az alábbi parancsok érhetők el a kezdőcsomagon belül:

- `npm run db`: Migrációk és a seeder futtatása nulláról (tiszta adatbázis)
- `npm run dev`: Szerver futtatása (fejlesztői)
- `npm run test`: Automata tesztelő futtatása minden feladatra
- `npm run test 3 4`: Automata tesztelő futtatása konkrét feladat(ok)ra a feladatok sorszáma alapján (pl. itt a 3. és 4. feladatra)
- `npm run zip`: ZH becsomagolása (automatikus nyilatkozat ellenőrzéssel és kitöltéssel)
- `npm run prettier`: A projektben lévő összes `.js` és `.json` fájl formázása Prettier-el

## Ajánlott VSCode kiegészítők

A kezdőcsomaghoz vannak ajánlott VSCode kiegészítők (lásd `./vscode/extensions.json`). Amikor először megnyitod VSCode-al a kezdőcsomagot, jobb alul felkínálja, hogy telepítsd őket. Ha nem, utólag is megtalálod őket a kiegészítők között "Workspace recommendations" néven:

![Workspace recommendations](https://i.imgur.com/NVjs2RX.png)
## Automatikus formázás mentéskor

Ha telepítetted az ajánlott kiegészítőket, köztük a Prettier plugint, akkor ha a kiegészítő beállításai között vagy a `./vscode/settings.json`-ben módosítod az automatikus mentést így (formatOnSave legyen true):

```json
"[javascript]": {
    "editor.formatOnSave": true
}
```

Akkor ha elmentesz egy fájlt a VSCode-ban, a Prettier automatikusan meghívásra kerül rá és megformázza a fájlban lévő kódot.

## Automatikus tesztelő, tudnivalók

A zárthelyihez automata tesztelőt biztosítunk, ami segít gyors visszajelzést adni a munkádról.

![Tesztelő](https://i.imgur.com/nECFXmg.png)

### Tesztelő telepítése a zárthelyi elején

A kezdőcsomagban alapból csak egy üres tesztelő van. A konkrét zárthelyi feladathoz tartozó tesztelőt a kezdés időpontjában osztjuk meg. A tartalmát be kell másolni a kezdőcsomag `test` mappájába, felülírva az ott lévő fájlokat.

Ez alapvetően két fájlt jelent:
- `inject.js`
  - A kezdőcsomag használja szerveroldalon, annak érdekében, hogy a tesztelő megfelelően el tudja indítani a teszt szervert és a kliensoldali tesztelést is hatékonyan elvégezhesse.
- `tester.js`
  - Maga a tesztelő

Ha zárthelyi közben valamiért módosítjuk és újra kiadjuk a tesztelőt, mondjuk egy időközben javított hiba miatt, akkor ugyanígy felül kell írni ismét.

### Fontos tudnivalók

Az alábbiakat vedd figyelembe a tesztelő használatakor:

- A tesztelő által adott eredmények csak tájékoztató jellegűek, melyektől a végleges értékelés pozitív és negatív irányba is eltérhet.
- **Nem az a feladat, hogy addig futtasd a tesztelőt, amíg minden át nem megy, hanem az, hogy a dolgozatot oldd meg a legjobb tudásod szerint! Ehhez a tesztelő csak egy segédlet, ami lehetőség szerint egy gyors visszajelzést ad a munkádról azáltal, hogy leteszteli a FŐBB eseteket, és ha azokon átmegy, akkor *valószínűleg* jó.**
- A tesztelőt igyekeztünk legjobb tudásunk és szándékunk szerint összeállítani, ennek ellenére elképzelhető, hogy egy hallgatónál valamilyen előre nem látott hiba miatt abszolút nem fut a tesztelő, vagy egyes részfeladatokat hibásan tesztel.
  - Ilyenkor megköszönjük, ha ezt jelzitek felénk, mivel így a jövőben elkerülhetővé tudunk tenni egy hibalehetőséget, illetve még a zh közben megpróbáljuk kijavítani a hibát is.
  - Fontos, ha ilyen történik, ne essetek kétségbe, hanem nyugodtan folytassátok a dolgozatot, hiszen azt az automata tesztelő nélkül, a gyakorlatokon tanult eszközökkel is meg kell tudni oldani! A feladat időkeretét is úgy szabjuk meg, hogy ebben az esetben is kényelmesen be lehet fejezni.
- Mivel a tesztelő csak egy segédlet, nem pedig a dolgozat kötelező része, ezért nem tudjuk elfogadni azt indoknak, hogy a zárthelyin a tesztelő miatt bármilyen hátrány ért!

## Automatikus zippelő, beadás

A zárthelyihez automatikus zippelőt biztosítunk, ami segít gyorsan és megfelelően kitölteni a nyilatkozatot, valamint összegyűjti és becsomagolja a beadáshoz szükséges fájlokat.

A program először ellenőrzi, hogy létezik-e már szabályosan kitöltött `statement.txt`. Ha nem, akkor felkínálja a nyilatkozatot elfogadásra, majd segít azt kitölteni. Ha a nyilatkozatfájl érvényes, akkor ezt a részt onnantól átugorja. Végül becsomagolja a fájlokat:

![Zipper](https://i.imgur.com/9fRUsQM.png)

Az alábbiakat vedd figyelembe a zippelő használatakor, illetve a beadásnál:

- A zippelőt igyekeztünk legjobb tudásunk és szándékunk szerint összeállítani, többszörösen teszteltük, ennek ellenére fontos, hogy beadás előtt nyisd meg a zip fájlt és ellenőrizd, hogy minden benne van-e, amin dolgoztál és amit be szeretnél adni! Ezt akkor is tedd meg, ha nem a mi zippelőnket használod, hiszen egy váratlan hiba bármikor történhet!
- A dolgozat megfelelő és hiánytalan beadása a hallgató felelőssége. Erre mindig időben fel is szoktuk hívni a figyelmet, illetve a dolgozat végén külön 15 perces időkeretet adunk a feladat megfelelő, nyugodt körülmények közötti beadására. Ebből kifolyólag ilyen ügyben nem tudunk utólagos reklamációknak helyt adni. Tehát ha valaki a zh után jelzi, hogy egy fájlt nem adott be, akkor azt sajnos nem tudjuk elfogadni.
