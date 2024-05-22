# Szerveroldali webprogramozás - Node.js zárthelyi

Tartalom:

- [Szerveroldali webprogramozás - Node.js zárthelyi](#szerveroldali-webprogramozás---nodejs-zárthelyi)
  - [Tudnivalók](#tudnivalók)
  - [Hasznos hivatkozások](#hasznos-hivatkozások)
  - [Kezdőcsomag segédlet](#kezdőcsomag-segédlet)
  - [Feladatok](#feladatok)
    - [I. rész: Sequelize (10 pont, min. 4 pont elérése szükséges!)](#i-rész-sequelize-10-pont-min-4-pont-elérése-szükséges)
      - [1. feladat: Modellek és kapcsolatok (5 pont)](#1-feladat-modellek-és-kapcsolatok-5-pont)
      - [2. feladat: Seeder (5 pont)](#2-feladat-seeder-5-pont)
    - [II. rész: REST API (20 pont, min. 8 pont elérése szükséges!)](#ii-rész-rest-api-20-pont-min-8-pont-elérése-szükséges)
      - [3. feladat: `GET /locations` (1 pont)](#3-feladat-get-locations-1-pont)
      - [4. feladat: `GET /locations/:id` (2 pont)](#4-feladat-get-locationsid-2-pont)
      - [5. feladat: `POST /locations` (2 pont)](#5-feladat-post-locations-2-pont)
      - [6. feladat: `DELETE /locations/:id` (1 pont)](#6-feladat-delete-locationsid-1-pont)
      - [7. feladat: `POST /login` (3 pont)](#7-feladat-post-login-3-pont)
      - [8. feladat: `GET /local-weather-log` (3 pont)](#8-feladat-get-local-weather-log-3-pont)
      - [9. feladat: `POST /insert-many` (4 pont)](#9-feladat-post-insert-many-4-pont)
      - [10. feladat: `POST /issue-warning` (4 pont)](#10-feladat-post-issue-warning-4-pont)
    - [III. rész: GraphQL (20 pont, min. 8 pont elérése szükséges!)](#iii-rész-graphql-20-pont-min-8-pont-elérése-szükséges)
      - [11. feladat: `Query.locations` és `Query.weather` (2 pont)](#11-feladat-querylocations-és-queryweather-2-pont)
      - [12. feladat: `Query.location` (1 pont)](#12-feladat-querylocation-1-pont)
      - [13. feladat: `Weather.location` (1 pont)](#13-feladat-weatherlocation-1-pont)
      - [14. feladat: `Mutation.createWeather` (3 pont)](#14-feladat-mutationcreateweather-3-pont)
      - [15. feladat: `Weather.warnings` (2 pont)](#15-feladat-weatherwarnings-2-pont)
      - [16. feladat: `Location.currentTemp` (3 pont)](#16-feladat-locationcurrenttemp-3-pont)
      - [17. feladat: `Mutation.setPublic` (3 pont)](#17-feladat-mutationsetpublic-3-pont)
      - [18. feladat: `Query.statistics` (5 pont)](#18-feladat-querystatistics-5-pont)

## Tudnivalók

<details>
<summary>Tudnivalók megjelenítése</summary>

- A zárthelyi megoldására **240 perc** áll rendelkezésre, amely a kidolgozás mellett **magába foglalja** a kötelező nyilatkozat értelmezésére és kitöltésére, a feladatok elolvasására, az anyagok letöltésére, összecsomagolására és feltöltésére szánt időt is.
- A kidolgozást a Canvas rendszeren keresztül kell beadni egyetlen **.zip** állományként. **A rendszer pontban 17:00-kor lezár, ezután nincs lehetőség beadásra!**
- A beadást és nyilatkozat kitöltését megkönnyítendő létrehoztunk egy parancsot a kezdőcsomagban, amely `npm run zip` hívásával futtatható. Igyekeztünk a legjobb tudásunk szerint elkészíteni csomagolót, de beadás előtt mindenképpen ellenőrizd a `zipfiles` mappában létrejött állomány tartalmát! **A helyes és hiánytalan feltöltés ellenőrzése a hallgató feladata, az ebből fakadó hibákért felelősséget nem vállalunk!**
- A `node_modules` könyvtár beadása **TILOS!**
- A megfelelően kitöltött `statement.txt` (nyilatkozat) nélküli megoldásokat **nem értékeljük**. Ha esetleg a nyilatkozat kimaradt vagy hibás, utólag Canvas hozzászólásban pótolható.
- A feladatok megoldásához **bármilyen segédanyag használható** (pl. dokumentáció, előadás, órai anyag, cheat sheet, stb.), viszont a **zárthelyi időtartamában emberi segítség** (pl. szinkron, aszinkron, chat, fórum, stb.) **és mesterséges intelligencia** (pl. ChatGPT, Bing AI, GitHub Copilot, Tabnine, stb.) igénybe vétele tilos! Ezek elfogadásáról nyilatkoztok az előző pontban említett nyilatkozatban, amelyben egyben tudomásul is veszitek az ELTE HKR szerinti következményeket.
- A feladatokat **Node.js** környezetben, **JavaScript** nyelven kell megoldani, a **tantárgy keretein belül tanult** technológiák használatával és a biztosított **kezdőcsomagból kiindulva**! 
</details>

## Hasznos hivatkozások

<details>
<summary>Hasznos hivatkozások megjelenítése</summary>

- Dokumentációk
  - [Sequelize dokumentáció](https://sequelize.org/master/)
  - [Model querying - basics](https://sequelize.org/docs/v6/core-concepts/model-querying-basics/)
  - [Model querying - finders](https://sequelize.org/docs/v6/core-concepts/model-querying-finders/)
  - [Sequelize asszociációk](https://github.com/szerveroldali/leirasok/blob/main/SequelizeAsszociaciok.md) (tantárgyi leírás)
  - [GraphQL dokumentáció](https://graphql.org/learn/)
  - [GraphQL skalárok (kezdőcsomag tartalmazza)](https://www.graphql-scalars.dev/docs)
  - [Fastify dokumentáció](https://www.fastify.io/docs/latest/)
- Eszközök:
  - [SQLite Viewer (VS Code kiegészítő)](https://marketplace.visualstudio.com/items?itemName=qwtel.sqlite-viewer)
  - [Firecamp (Chrome kiegészítő)](https://chrome.google.com/webstore/detail/firecamp-a-campsite-for-d/eajaahbjpnhghjcdaclbkeamlkepinbl)
  - [DB Browser for SQLite](https://sqlitebrowser.org/)
  - [Postman](https://www.postman.com/)

</details>

## Kezdőcsomag segédlet

<details>
<summary>Kezdőcsomag segédlet megjelenítése</summary>

A zárthelyihez kezdőcsomagot biztosítunk, amelynek használata **kötelező**. A csomagot GitHub-ról lehet letölteni, a függőségek telepítése után (`npm i`) kezdhető meg a fejlesztés.

- A kezdőcsomag elérhető ebben a GitHub repository-ban:
  - https://github.com/szerveroldali/zh_kezdocsomag
  - vagy: [Közvetlen letöltési link](https://github.com/szerveroldali/zh_kezdocsomag/archive/refs/heads/main.zip) _(.zip fájl)_
- Hasznos parancsok:
  - `npm run db` - adatbázis migrációk futtatása (üres adatbázisból indulva) és seedelés
  - `npm run dev` - `server.js` futtatása watch módban (változtatásra a szerver újraindul)
  - `npm run zip` - zárthelyi becsomagolása
  - `npm run test` - automatikus tesztelő
- Az automatikus tesztelőről
  - **FONTOS! Nem az a feladat, hogy addig futtasd a tesztelőt, amíg minden át nem megy; hanem az, hogy a dolgozatot oldd meg a legjobb tudásod szerint! Ehhez a tesztelő csak egy segédlet, ami lehetőség szerint egy gyors visszajelzést ad a munkádról azáltal, hogy leteszteli a főbb eseteket, és ha azokon átmegy a megoldásod, akkor _valószínűleg_ jó.**
  - A tesztelő használata nem kötelező.
  - A tesztelő által adott eredmények csak tájékoztató jellegűek, melyektől a végleges értékelés pozitív és negatív irányba is eltérhet.
  - Használat módja:
    - `npm run test` - minden feladat tesztelése
    - pl. `npm run test 3 5` - csak a 3. és 5. feladat tesztelése
  - Természetesen a tesztelő csak akkor fog működni, ha a kezdőcsomag `test` könyvtárába elhelyezésre kerültek a zárthelyi idején közzétett fájlok!
  </details>

## Feladatok

Készíts _Node.js_ környezetben, _JavaScript_ nyelv használatával egy **REST API**-t és **GraphQL API**-t az alábbi pontoknak megfelelően! A tantárgy gyakorlati tematikájához illeszkedve adatbázisként _SQLite3_, ORM-ként _Sequelize_, kiszolgálóként pedig _Fastify_ keretrendszer használata **kötelező!** (A zárthelyihez kiadott [kezdőcsomagban](https://github.com/szerveroldali/zh_kezdocsomag) ezen csomagok már inicializálva vannak.)

### I. rész: Sequelize (10 pont, min. 4 pont elérése szükséges!)

#### 1. feladat: Modellek és kapcsolatok (5 pont)

Hozd létre az alábbi modelleket Sequelize CLI használatával! Az `id`, `createdAt` és `updatedAt` mezők a alapértelmezetten létrejönnek, így ezeket a feladat bővebben nem fejti ki. Alapesetben egyik mező értéke sem lehet `null`, hacsak nem adtunk külön `nullable` kikötést! Tehát alapértelmezés szerint a migráció minden mezőjére
```js
allowNull: false
```
van érvényben, ahol a feladat ettől eltérően nem jelzi.

A következő modelleket kell kigenerálni/létrehozni: 

- `Location` - mérőhely
  - `id`
  - `name` - string, a mérőhely megnevezése, egyedi (unique)
  - `lat` - float, a mérőhely földrajzi szélessége
  - `lon` - float, a mérőhely földrajzi hosszúsága
  - `public` - boolean, a mérőhely publikusan megközelíthető-e, alapértelmezetten igaz
  - `createdAt`
  - `updatedAt`
- `Weather` - időjárásmérési naplóadat
  - `id`
  - `type` - string, az időjárás szöveges leírása (pl. `sunny`)
  - `LocationId` - integer, hivatkozás a naplóadat mérőhelyének azonosítójára
  - `temp` - float, a mért hőmérséklet Celsius-fokban
  - `loggedAt` - datetime, a mérés időpontja (ez ne egyezzen a `createdAt` mezővel, hanem változatos múltbeli időpontok legyenek)
  - `createdAt`
  - `updatedAt`
- `Warning` - figyelmeztetések
  - `id`
  - `level` - integer, a figyelmeztetés szintje
  - `message` - string, a figyelmeztetés szövege, lehet `null`
  - `createdAt`
  - `updatedAt`

**(Angol nyelvi megjegyzés: a `weather` szó többes számban is `weather` - ezt hasznos lehet fejben tartani!)**

A fenti modellek közötti asszociációk (kapcsolatok):

- `Location` 1 : N `Weather`
- `Weather` N : N `Warning`

_Természetesen 1:N kapcsolatot egy felvett mezővel, N:N kapcsolatot pedig külön kapcsolótábla létrehozásával tárolunk. A kapcsolótábla neve az összekapcsolt modellek nevéből képzendő betűrendi sorrendben! Mivel a feladatok között törlés is van, kapcsolatoknál ügyelj a hivatkozási épségre, használj `cascade` mechanizmust!_

#### 2. feladat: Seeder (5 pont)

Hozz létre egy seedert, melynek segítségével feltölthető az adatbázis mintaadatokkal!

A megoldás akkor számít teljes értékűnek (tehát csak akkor jár a teljes pontszám), ha az alábbiak **mindegyike** teljesül:
1. a seeder mindegyik modellből generál észszerű számban
2. a generált modellek minden esetet lefednek (pl. egy nullable mező néhol ki van töltve adattal, néhol pedig `null` értékű; logikai mező változatosan igaz vagy hamis, stb.)
3. a kapcsolatok (relációk / asszociációk) is minden esetben létrejönnek a modellek között

A seedert az automatikus tesztelő nem tudja értékelni, minden esetben a gyakorlatvezető fogja kézzel pontozni.

### II. rész: REST API (20 pont, min. 8 pont elérése szükséges!)

#### 3. feladat: `GET /locations` (1 pont)

Lekéri az összes létező mérőhelyet.

- Minta kérés: `GET http://localhost:4000/locations`
- Válasz helyes kérés esetén: `200 OK`
```json
[
  {
    "id": 1,
    "name": "South Issacchester",
    "lat": 54.54167161602527,
    "lon": 63.37276521138847,
    "public": true,
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  },
  stb.
]
```

#### 4. feladat: `GET /locations/:id` (2 pont)

Lekéri a megadott azonosítójú mérőhely adatait.

- Minta kérés: `GET http://localhost:4000/locations/4`
- Válasz, ha az `id` paraméter hiányzik vagy nem egész szám: `400 BAD REQUEST`
- Válasz, ha a megadott `id`-vel nem létezik mérőhely: `404 NOT FOUND`
- Válasz helyes kérés esetén: `200 OK`
```json
{
  "id": 4,
  "name": "North Rubye",
  "lat": 36.213814965449274,
  "lon": 127.18256168067455,
  "public": true,
  "createdAt": "2023-05-31T...",
  "updatedAt": "2023-05-31T..."
}
```

#### 5. feladat: `POST /locations` (2 pont)

Létrehoz egy új mérőhelyet a kérés törzsében (body) megadott adatokkal. A törzsnek minden alapértelmezett érték nélküli mezőt kötelezően tartalmaznia kell; tehát a `public` mező hiányozhat, amely esetben a létrehozott entitás és visszaadott válaszobjektum ezen mezője alapértelmezetten `true` értéket kell felvegyen.

_(A valóságban nyilván hitelesített felhasználó hozna létre entitásokat, de a zárthelyi ezen feladatánál egyelőre nem szükséges ezzel foglalkozni, bárki meghívhatja a végpontot.)_

- Minta kérés: `POST http://localhost:4000/locations`
```json
{
  "name": "Lunaville",
  "lat": 47.4972,
  "lon": 19.0402
}
```
- Válasz, ha a kérés törzse (body) hiányos vagy típushibás adatot tartalmaz: `400 BAD REQUEST`
- Válasz, ha a megadott névvel már létezik mérőhely: `500 INTERNAL SERVER ERROR`
- Válasz helyes kérés esetén: `201 CREATED`
```json
{
  "id": ...,
  "name": "Lunaville",
  "lat": 47.4972,
  "lon": 19.0402,
  "public": true,
  "createdAt": "2023-05-31T...",
  "updatedAt": "2023-05-31T..."
}
```

#### 6. feladat: `DELETE /locations/:id` (1 pont)

Az adott azonosítójú mérőhely törlése. (Amennyiben a modellezés részben helyesen dolgoztál, ilyen esetben a mérőhelyhez tartozó mért adatok is törlődnek majd.)

_(Hitelesítés ennél a feladatnál sem szükséges még.)_

- Minta kérés: `DELETE http://localhost:4000/locations/4`
- Válasz, ha az `id` paraméter hiányzik vagy nem egész szám: `400 BAD REQUEST`
- Válasz, ha a megadott `id`-vel nem létezik mérőhely: `404 NOT FOUND`
- Válasz helyes kérés esetén: `200 OK`

A válasz tartalma tetszőleges lehet, a feladatnál csak a státuszkódot ellenőrizzük!

#### 7. feladat: `POST /login` (3 pont)

**Hitelesítés.** Ezen a végponton keresztül lehet bejelentkezni **mérőhelyként**, tehát ebben a feladatsorban nem nevesített felhasználókat, hanem mérőhelyeket kezelünk hitelesítés szempontjából. A végpontra egy e-mail címet kell küldeni `locationX@weather.org` formátumban, ahol `X` egy egész szám. Ha létezik `X` azonosítójú mérőhely, akkor kiállítjuk a tokent, amelynek payloadjában a **mérőhely** adatai szerepelnek.

_(Technikai részletek: A token aláírásához `HS256` algoritmust használj `secret` titkosító kulccsal! A kiadott kezdőcsomag alapbeállításon erre a működésre van konfigurálva, de ha szükségessé válna a [tokent ellenőrizni](https://jwt.io/), akkor hasznos ezekről tudni. Ha bizonytalan vagy a tesztelő által várt payload formátumban, ezen az oldalon kiolvashatod a mintaként adott token tartalmát is.)_

- Minta kérés: `POST http://localhost:4000/login`
```json
{
  "email": "location4@weather.org"
}
```
- Válasz, ha a kérés formailag hibás (pl. nincs `email` mező): `400 BAD REQUEST`
- Válasz, ha az e-mail cím nem az előírt formátumot követi: `418 I'M A TEAPOT`
- Válasz, ha az e-mail címben található azonosítóval nem létezik mérőhely: `404 NOT FOUND`
- Válasz helyes kérés esetén: `200 OK`
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6NCwibmFtZSI6Ik5vcnRoIFJ1YnllIiwibGF0IjozNi4yMTM4MTQ5NjU0NDkyNzQsImxvbiI6MTI3LjE4MjU2MTY4MDY3NDU1LCJwdWJsaWMiOnRydWUsImNyZWF0ZWRBdCI6IjIwMjMtMDUtMzFULi4uIiwidXBkYXRlZEF0IjoiMjAyMy0wNS0zMVQuLi4iLCJpYXQiOjE2ODUxNzkxNDl9.Ylc7Nz_6kmQ9oj86KHhb68_AmqKaIuAg8xqCbowC8bo"
}
```

#### 8. feladat: `GET /local-weather-log` (3 pont)

**Hitelesített végpont!** Növekvő időrendi sorrendben (`loggedAt` mező szerint) visszaadja az összes olyan időjárási adatot, amely a hitelesített mérőhelyhez tartozik.

Emlékeztető! A hitelesített végpontokra a következő fejléccel kell kérést küldeni:
```
Authorization: Bearer <token>
```

_(Technikai segítség: a Fastify szempontjából mindegy, hogy milyen információt tárolsz a payloadban, tehát a mérőhelyre is úgy kell a kérésnél hivatkozni, mintha egy felhasználót keresnél vissza a payloadból!)_

- Minta kérés: `GET http://localhost:4000/local-weather-log`
- Válasz hitelesítetlen kérés esetén: `401 UNAUTHORIZED`
- Válasz helyes kérés esetén: `200 OK`
```json
[
  {
    "id": 54,
    "type": "cloudy",
    "LocationId": 4,
    "temp": -4.556542574428022,
    "loggedAt": "2019-02-03T02:35:46.211Z",
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  },
  {
    "id": 20,
    "type": "snow",
    "LocationId": 4,
    "temp": -2.899402305483818,
    "loggedAt": "2020-04-02T17:17:49.170Z",
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  },
  stb.
]
```

#### 9. feladat: `POST /insert-many` (4 pont)

**Hitelesített végpont!** Új időjárási (hőmérsékleti) adatokat visz fel az adatbázisba a hitelesített mérőhelyhez úgy, hogy egy kéréssel több bejegyzést hoz létre, amelyeknél az időjárás típusa megjegyezik, a feljegyzések időpontjai pedig a kérésben adott időközönként követik egymást. Helyes kérés esetén a válasz tartalmazza az összes létrejött időjárásmérési adatot. (Lásd a minta kérést és választ!)

- Minta kérés: `POST http://localhost:4000/insert-many`
```js
{
  "type" : "cloudy",
  "startTime": "2023-05-31T08:30:00Z", // feltehető, hogy ha string, akkor ilyen formátumban van
  "interval": 15,                      // bejegyzések közötti idő percekben kifejezve
  "temps": [3.1415, 1.618, 2.718]      // számokat tartalmazó tömb
}
```
- Válasz formailag helytelen kérés esetén: `400 BAD REQUEST`
- Válasz hitelesítetlen kérés esetén: `401 UNAUTHORIZED`
- Válasz helyes kérés esetén: `200 OK`
```json
[
  {
    "id": ...,
    "type": "cloudy",
    "loggedAt": "2023-05-31T08:30:00.000Z",
    "temp": 3.1415,
    "LocationId": 4,
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  },
  {
    "id": ...,
    "type": "cloudy",
    "loggedAt": "2023-05-31T08:45:00.000Z",
    "temp": 1.618,
    "LocationId": 4,
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  },
  {
    "id": ...,
    "type": "cloudy",
    "loggedAt": "2023-05-31T09:00:00.000Z",
    "temp": 2.718,
    "LocationId": 4,
    "createdAt": "2023-05-31T...",
    "updatedAt": "2023-05-31T..."
  }
]
```

#### 10. feladat: `POST /issue-warning` (4 pont)

**Hitelesített végpont!** Segítségével egy megadott időjárási adathoz figyelmeztetést tud csatolni az a mérőhely, aki az időjárási adatot feljegyezte. Emlékeztető: az időjárásadatok és a figyelmeztetések között N:N kapcsolat van, hozzácsatoláskor az eddigi kapcsolatok is meg kell maradjanak!

- Minta kérés: `POST http://localhost:4000/issue-warning`
```json
{
  "WeatherId": 3,
  "WarningId": 2
}
```
- Válasz formailag hibás kérés esetén: `400 BAD REQUEST`
- Válasz hitelesítetlen kérés esetén: `401 UNAUTHORIZED`
- Válasz, ha nem létezik a megadott azonosítóval időjárás adat vagy figyelmeztetés: `404 NOT FOUND`
- Válasz, ha az időjárásadatot nem a hitelesített mérőhely jegyezte fel: `403 FORBIDDEN`
- Válasz, ha már eleve van ilyen figyelmeztetés hozzárendelve az időjárási adathoz: `409 CONFLICT`
- Válasz helyes kérés esetén: `201 CREATED`

A válasz tartalma tetszőleges lehet, a feladatnál csak a státuszkódot ellenőrizzük!

### III. rész: GraphQL (20 pont, min. 8 pont elérése szükséges!)

#### 11. feladat: `Query.locations` és `Query.weather` (2 pont)

Minden mérőhely és minden időjárásadat elemi mezőinek lekérése.

Ennek a feladatnak része a megfelelő típusdefiníciók létrehozása is a sémában!

Kérés:
```graphql
query {
  locations {
    id
    name
    lat
    lon
    public
    createdAt
    updatedAt
  }
  weather {
    id
    type
    LocationId
    temp
    loggedAt
    createdAt
    updatedAt
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "locations": [
      {
        "id": "1",
        "name": "South Issacchester",
        "lat": 54.54167161602527,
        "lon": 63.37276521138847,
        "public": true,
        "createdAt": "2023-05-31T...",
        "updatedAt": "2023-05-31T..."
      },
      stb.
    ],
    "weather": [
      {
        "id": "1",
        "type": "rain",
        "LocationId": "12",
        "temp": 16.839904966764152,
        "loggedAt": "2019-01-23T13:38:40.030Z",
        "createdAt": "2023-05-31T...",
        "updatedAt": "2023-05-31T..."
      },
      stb.
    ]
  }
}
```

#### 12. feladat: `Query.location` (1 pont)

Legyen lehetőség egy mérőhely adatait annak azonosítója alapján lekérdezni. Ha nem létezik ilyen azonosítójú mérőhely, akkor `null` választ kell adni.

Kérés:
```graphql
query{
  location (id: 4){
    id
    name
    lat
    lon
    public
    createdAt
    updatedAt
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "location": {
      "id": "4",
      "name": "North Rubye",
      "lat": 36.213814965449274,
      "lon": 127.18256168067455,
      "public": true,
      "createdAt": "2023-05-31T...",
      "updatedAt": "2023-05-31T..."
    }
  }
}
```

#### 13. feladat: `Weather.location` (1 pont)

Legyen lehetőség az időjárási adatok felől indulva lekérdezni a hozzá tartozó mérőhely adatait!

Kérés:
```graphql
query {
  weather {
    id
    type
    location {
      id
      name
      lat
      lon
    }
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "weather": [
      {
        "id": "1",
        "type": "rain",
        "location": {
          "id": "12",
          "name": "South Issacchester",
          "lat": 54.54167161602527,
          "lon": 63.37276521138847
        }
      },
      stb.
    ]
  }
}
```

#### 14. feladat: `Mutation.createWeather` (3 pont)

Új időjárásmérési adat felvétele. Siker esetén visszaadja a létrejött terméket, különben `null` értéket kap.

A bemenő adatoknak adj meg a sémában egy `CreateWeatherInput` definiciót, amely a modellben tárolt mezőket várja az automatikusan kitöltődő `id`, `createdAt` és `updatedAt` kivételével!

```graphql
mutation {
  createWeather(input: {
    type: "sunny"
    LocationId: 1
    temp: 22.4
    loggedAt: "1996-07-09T10:25:00Z"
  }) { 
    id
    type
    LocationId
    temp
    loggedAt
    createdAt
    updatedAt
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "createWeather": {
      "id": "...",
      "type": "sunny",
      "LocationId": "1",
      "temp": 22.4,
      "loggedAt": "1996-07-09T10:25:00.000Z",
      "createdAt": "2023-05-31T...",
      "updatedAt": "2023-05-31T..."
    }
  }
}
```

#### 15. feladat: `Weather.warnings` (2 pont)

Legyen lehetőség az időjárási adatok felől indulva lekérdezni az adott mérésre kiadott figyelmeztetéseket. A figyelmeztetéseket riasztási szint szerint csökkenő sorrendben add meg!

Kérés:
```graphql
query {
  weather {
    id
    type
    warnings {
      id
      level
      message
    }
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "weather": [
      {
        "id": "1",
        "type": "rain",
        "warnings": [
          {
            "id": "6",
            "level": 5,
            "message": "Tenetur earum odit quasi vel atque deleniti. Distinctio doloribus fugiat perspiciatis blanditiis atque eius. Doloremque recusandae reprehenderit culpa."
          },
          {
            "id": "2",
            "level": 4,
            "message": null
          },
          stb.
        ]
      },
      stb.
    ]
  }
}
```

#### 16. feladat: `Location.currentTemp` (3 pont)

Legyen lehetőség a mérőhelyek felől indulva lekérni azt, hogy az adott mérőhelyhez tartozó legfrissebb bejegyzés (`loggedAt` mező legnagyobb) szerint mennyi ott a hőmérséklet. Ha egy mérőhelyhez nem tartozik még időjárási adat, akkor `null` legyen az eredmény!

Kérés:
```graphql
query {
  locations {
    id
    currentTemp
  }
}
```

Mintaválasz: 
```json
{
  "data": {
    "locations": [
      {
        "id": "1",
        "currentTemp": 22.207078323699534
      },
      {
        "id": "2",
        "currentTemp": null
      },
      {
        "id": "3",
        "currentTemp": 14.92278060875833
      },
      stb.
    ]
  }
}
```

#### 17. feladat: `Mutation.setPublic` (3 pont)

Ezzel a mutációval módosítani lehet a `public` mezőjét egy megadott azonosítójú mérőhelynek.

Az eredmény minden esetben egy string legyen az alábbiak szerint:
- `NOT FOUND` - ha nem létezik mérőhely a megadott azonosítóval
- `ALREADY SET` - ha a mérőhely public mezője már eleve a kivánt értékkel szerepelt
- `CHANGED` - ha a mérőhely public mezője most került megváltoztatásra

Kérés:
```graphql
mutation {
  setPublic(LocationId: 3, public: false)
}
```

Mintaválasz:
```json
{
  "data": {
    "setPublic": "ALREADY SET"
  }
}
```

#### 18. feladat: `Query.statistics` (5 pont)

Statisztika készítése, amely a következő mezőket tartalmazza:

- `locationCount`: a mérőhelyek darabszáma 
- `averageTemp`: a globális átlaghőmérséklet az összes időjárásmérési adat alapján
- `over30Celsius`: azon időjárásmérési adatok darabszáma, ahol a hőmérséklet nagyobb mint 30 °C
- `multipleWarnings`: azon időjárásmérési adatok darabszáma, amelynél legalább 2 figyelmezetés van érvényben
- `mostActiveLocation`: azon mérőhely adatai, ahonnan a legtöbb mérési adat származik (több esetén a legkisebb azonosítójú ilyen mérőhely)

(Feltételezhető, hogy a statisztika készítésekor mindig van legalább egy időjárásadat az adatbázisban.)

Kérés:
```graphql
query {
  statistics {
    locationCount
    averageTemp
    over30Celsius
    multipleWarnings
    mostActiveLocation {
      id
      name
      lat
      lon
    }
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "statistics": {
      "locationCount": 17,
      "averageTemp": 8.022858120396782,
      "over30Celsius": 13,
      "multipleWarnings": 54,
      "mostActiveLocation": {
        "id": "4",
        "name": "North Rubye",
        "lat": 36.213814965449274,
        "lon": 127.18256168067455
      }
    }
  }
}
```
