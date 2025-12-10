# Szerveroldali webprogramozás - API zárthelyi

Tartalom:

- [Szerveroldali webprogramozás - API zárthelyi](#szerveroldali-webprogramozás---api-zárthelyi)
  - [Tudnivalók](#tudnivalók)
  - [Feladatok](#feladatok)
    - [Adatbázis](#adatbázis)
    - [I. rész: REST API (30 pont, min. 12 pont elérése szükséges!)](#i-rész-rest-api-30-pont-min-12-pont-elérése-szükséges)
      - [1. feladat: `GET /locations` (2 pont)](#1-feladat-get-locations-2-pont)
      - [2. feladat: `GET /locations/:id` (4 pont)](#2-feladat-get-locationsid-4-pont)
      - [3. feladat: `POST /locations` (4 pont)](#3-feladat-post-locations-4-pont)
      - [4. feladat: `DELETE /locations/:id` (4 pont)](#4-feladat-delete-locationsid-4-pont)
      - [5. feladat: `POST /login` (4 pont)](#5-feladat-post-login-4-pont)
      - [6. feladat: `GET /local-weather-log` (6 pont)](#6-feladat-get-local-weather-log-6-pont)
      - [7. feladat: `POST /insert-many` (6 pont)](#7-feladat-post-insert-many-6-pont)
    - [II. rész: GraphQL (20 pont, min. 8 pont elérése szükséges!)](#ii-rész-graphql-20-pont-min-8-pont-elérése-szükséges)
      - [8. feladat: `Query.locations` és `Query.weather` (2 pont)](#8-feladat-querylocations-és-queryweather-2-pont)
      - [9. feladat: `Query.location` (1 pont)](#9-feladat-querylocation-1-pont)
      - [10. feladat: `Weather.location` (1 pont)](#10-feladat-weatherlocation-1-pont)
      - [11. feladat: `Mutation.createWeather` (3 pont)](#11-feladat-mutationcreateweather-3-pont)
      - [12. feladat: `Weather.warnings` (2 pont)](#12-feladat-weatherwarnings-2-pont)
      - [13. feladat: `Location.currentTemp` (3 pont)](#13-feladat-locationcurrenttemp-3-pont)
      - [14. feladat: `Mutation.setPublic` (3 pont)](#14-feladat-mutationsetpublic-3-pont)
      - [15. feladat: `Query.statistics` (5 pont)](#15-feladat-querystatistics-5-pont)

## Tudnivalók

<details>
<summary>Szabályok megjelenítése</summary>

- A zárthelyi megoldására **150 perc** áll rendelkezésre, amely a kidolgozás mellett **magába foglalja** a kötelező nyilatkozat értelmezésére és kitöltésére, a feladatok elolvasására, az anyagok letöltésére, összecsomagolására és feltöltésére szánt időt is.
- A kidolgozást a Canvas rendszeren keresztül kell beadni egyetlen **.zip** állományként. **A rendszer pontban 18:45-kor lezár, ezután nincs lehetőség beadásra!**
- A beadást és nyilatkozat kitöltését megkönnyítendő létrehoztunk egy parancsot a kezdőcsomagban, amely `npm run zip` hívásával futtatható. Igyekeztünk a legjobb tudásunk szerint elkészíteni csomagolót, de beadás előtt mindenképpen ellenőrizd a `zipfiles` mappában létrejött állomány tartalmát! **A helyes és hiánytalan feltöltés ellenőrzése a hallgató feladata, az ebből fakadó hibákért felelősséget nem vállalunk!**
- A `vendor` és `node_modules` (ha létezik) könyvtár beadása **TILOS!**
- A megfelelően kitöltött `STATEMENT.md` (nyilatkozat) nélküli megoldásokat **nem értékeljük**. Ha esetleg a nyilatkozat kimaradt vagy hibás, utólag Canvas hozzászólásban pótolható.
- A feladatok megoldásához **bármilyen segédanyag használható** (pl. dokumentáció, előadás, órai anyag, cheat sheet, stb.), viszont a **zárthelyi időtartamában emberi segítség** (pl. szinkron, aszinkron, chat, fórum, stb.) **és mesterséges intelligencia** (pl. ChatGPT, Bing AI, GitHub Copilot, Tabnine, stb.) igénybe vétele tilos! Ezek elfogadásáról nyilatkoztok az előző pontban említett nyilatkozatban, amelyben egyben tudomásul veszitek, hogy nem megengedett segítség igénybevétele esetén a tárgyból **végleges elégtelen** érdemjegy jár, javítási/pótlási lehetőség nincs!
- A feladatokat **Laravel** környezetben, **PHP** nyelven kell megoldani, a **tantárgy keretein belül tanult** technológiák használatával és a biztosított **kezdőcsomagból kiindulva**! 
</details>


<details>
<summary>Kezdőcsomag beüzemelése</summary>

1. `cp .env.example .env`
2. `composer install`
3. `php artisan migrate --seed`
4. `php artisan key:generate`
5. `php artisan serve`
</details>

## Feladatok

### Adatbázis

A feladathoz a kezdőcsomagban készen adjuk a migrációkat, modelleket és seedert. Az alábbi modellekkel kell dolgozni:

- `Location` - mérőhely
  - `id`
  - `name` - string, a mérőhely megnevezése, egyedi (unique)
  - `email` - string, a mérőhelybe bejelentkezéshez használt email cím, egyedi (unique)
  - `password` - string, jelszóhash, rejtett mező
  - `lat` - float, a mérőhely földrajzi szélessége
  - `lon` - float, a mérőhely földrajzi hosszúsága
  - `public` - boolean, a mérőhely publikusan megközelíthető-e, alapértelmezetten igaz
  - `created_at`
  - `updated_at`
- `Weather` - időjárásmérési naplóadat
  - `id`
  - `type` - string, az időjárás szöveges leírása (pl. `sunny`)
  - `location_id` - integer, hivatkozás a naplóadat mérőhelyének azonosítójára
  - `temp` - float, a mért hőmérséklet Celsius-fokban
  - `logged_at` - timestamp, a mérés időpontja (ez nem egyezik a `created_at` mezővel, hanem változatos múltbeli időpontokat tartalmazhat)
  - `created_at`
  - `updated_at`
- `Warning` - figyelmeztetések
  - `id`
  - `level` - integer, a figyelmeztetés szintje
  - `message` - string, a figyelmeztetés szövege, lehet `null`
  - `created_at`
  - `updated_at`

**(Angol nyelvi megjegyzés: a `weather` szó többes számban is `weather` - ezt hasznos lehet fejben tartani!)**

A fenti modellek közötti kapcsolatok:

- `Location` 1 : N `Weather`
- `Weather` N : N `Warning`

_Természetesen 1:N kapcsolatot egy felvett mezővel, N:N kapcsolatot pedig külön kapcsolótábla létrehozásával tárolunk. A kapcsolótábla neve az összekapcsolt modellek nevéből képzendő betűrendi sorrendben! Mivel a feladatok között törlés is van, az adatbázisban `cascade` mechanizmust állítottunk be!_

### I. rész: REST API (30 pont, min. 12 pont elérése szükséges!)

#### 1. feladat: `GET /locations` (2 pont)

Lekéri az összes létező mérőhelyet.

- Minta kérés: `GET http://localhost:8000/api/locations`
- Válasz helyes kérés esetén: `200 OK`
```json
[
  {
    "id": 1,
    "name": "officiis qui rem",
    "email": "roscoe25@example.com",
    "lat": 1.84941,
    "lon": 169.19919,
    "public": true,
    "created_at": "2024-12-15T10:03:03.000000Z",
    "updated_at": "2024-12-15T10:03:03.000000Z"
  },
  stb.
]
```

#### 2. feladat: `GET /locations/:id` (4 pont)

Lekéri a megadott azonosítójú mérőhely adatait.

- Minta kérés: `GET http://localhost:8000/api/locations/4`
- Válasz, ha az `id` paraméter nem egész szám: `422 UNPROCESSABLE CONTENT`
- Válasz, ha a megadott `id`-vel nem létezik mérőhely: `404 NOT FOUND`
- Válasz helyes kérés esetén: `200 OK`
```json
{
  "id": 4,
  "name": "est ea voluptas",
  "email": "juliet39@example.com",
  "lat": 63.2048,
  "lon": 92.09794,
  "public": true,
  "created_at": "2024-12-15T10:03:03.000000Z",
  "updated_at": "2024-12-15T10:03:03.000000Z"
}
```

#### 3. feladat: `POST /locations` (4 pont)

Létrehoz egy új mérőhelyet a kérés törzsében (body) megadott adatokkal. A törzsnek minden alapértelmezett érték nélküli mezőt kötelezően tartalmaznia kell; tehát a `public` mező hiányozhat, amely esetben a létrehozott entitás és visszaadott válaszobjektum ezen mezője alapértelmezetten `true` értéket kell felvegyen.

_(A valóságban nyilván hitelesített felhasználó hozna létre entitásokat, de a zárthelyi ezen feladatánál egyelőre nem szükséges ezzel foglalkozni, bárki meghívhatja a végpontot.)_

Ne feledkezz meg arról, hogy a jelszót az adatbázisban nem nyers szövegként tároljuk! A jelszót hasításához használhatod akár a PHP nyelv beépített `password_hash()` függvényét is mentés előtt!

- Minta kérés: `POST http://localhost:8000/api/locations`
```json
{
  "name": "Lunaville",
  "email": "luna@ville.hu",
  "password": "password",
  "lat": 47.4972,
  "lon": 19.0402
}
```
- Válasz, ha a kérés törzse (body) hiányos vagy típushibás adatot tartalmaz: `422 UNPROCESSABLE CONTENT`
- Válasz, ha a kérésben szereplő név vagy email nem egyedi: `409 CONFLICT`
- Válasz helyes kérés esetén: `201 CREATED`
```json
{
  "id": ...,
  "name": "Lunaville",
  "email": "luna@ville.hu",
  "lat": 47.4972,
  "lon": 19.0402,
  "public": true,
  "createdAt": "2023-05-31T...",
  "updatedAt": "2023-05-31T..."
}
```

#### 4. feladat: `DELETE /locations/:id` (4 pont)

Az adott azonosítójú mérőhely törlése.

_(Hitelesítés ennél a feladatnál sem szükséges még.)_

- Minta kérés: `DELETE http://localhost:8000/api/api/locations/4`
- Válasz, ha az `id` paraméter nem egész szám: `422 UNPROCESSABLE CONTENT`
- Válasz, ha a megadott `id`-vel nem létezik mérőhely: `404 NOT FOUND`
- Válasz helyes kérés esetén: `200 OK`

A válasz tartalma tetszőleges lehet, a feladatnál csak a státuszkódot ellenőrizzük!

#### 5. feladat: `POST /login` (4 pont)

**Hitelesítés.** Ezen a végponton keresztül lehet bejelentkezni **mérőhelyként**, tehát ebben a feladatsorban nem nevesített felhasználókat, hanem mérőhelyeket kezelünk hitelesítés szempontjából. Erre a _Laravel Sanctum_ már fel van készítve. 

A bejelentkezéshez két adatot kell elküldeni: egy mérőhelyhez tartozó email címet, valamint a megfelelő jelszót, amely alapértelmezetten `password` minden mérőhelynél.

- Minta kérés: `POST http://localhost:8000/api/login`
```json
{
  "email": "location4@weather.org",
  "password": "password"
}
```
- Válasz, ha a kérés formailag hibás (pl. nincs `email` vagy `password` mező): `422 UNPROCESSABLE CONTENT`
- Válasz, ha nem létezik ilyen e-mail cím vagy helytelen a beírt jelszó: `401 UNAUTHORIZED`
- Válasz helyes kérés esetén: `201 CREATED`
```json
{
  "token": "3|VHAdpyvJghyyhc2xagdiWrFDeSQywCm38hKtOvcJ90d9d52a"
}
```

#### 6. feladat: `GET /local-weather-log` (6 pont)

**Hitelesített végpont!** Növekvő időrendi sorrendben (`logged_at` mező szerint) visszaadja az összes olyan időjárási adatot, amely a hitelesített mérőhelyhez tartozik.

Emlékeztető! A hitelesített végpontokra a következő fejléccel kell kérést küldeni:
```
Authorization: Bearer <token>
```

A hitelesített mérőhely visszakeresését a token alapján pedig az `auth:sanctum` middleware megoldja helyetted.

- Minta kérés: `GET http://localhost:8000/api/api/local-weather-log`
- Válasz hitelesítetlen kérés esetén: `401 UNAUTHORIZED`
- Válasz helyes kérés esetén: `200 OK`
```json
[
  {
    "id": 21,
    "type": "other",
    "location_id": 2,
    "temp": 30.9,
    "logged_at": "1996-02-07 15:08:15",
    "created_at": "2024-12-16T10:48:03.000000Z",
    "updated_at": "2024-12-16T10:48:03.000000Z"
  },
  {
    "id": 15,
    "type": "sunny",
    "location_id": 2,
    "temp": 31.2,
    "logged_at": "2000-03-10 13:13:33",
    "created_at": "2024-12-16T10:48:03.000000Z",
    "updated_at": "2024-12-16T10:48:03.000000Z"
  },
  stb.
]
```

#### 7. feladat: `POST /insert-many` (6 pont)

**Hitelesített végpont!** Új időjárási (hőmérsékleti) adatokat visz fel az adatbázisba a hitelesített mérőhelyhez úgy, hogy egy kéréssel több bejegyzést hoz létre, amelyeknél az időjárás típusa megjegyezik, a feljegyzések időpontjai pedig a kérésben adott időközönként követik egymást. Helyes kérés esetén a válasz tartalmazza az összes létrejött időjárásmérési adatot. (Lásd a minta kérést és választ!)

Tipp: dátum- és időszámításokhoz a [Carbon](https://carbon.nesbot.com/) nagyon jó, és már benne van a projektedben a `\Carbon\Carbon` névtér alatt!

- Minta kérés: `POST http://localhost:8000/api/insert-many`
```js
{
  "type" : "cloudy",                   // kötelező, string
  "startTime": "2023-05-31T08:30:00Z", // datetime - ellenőrizni kell
  "interval": 15,                      // bejegyzések közötti idő percekben kifejezve, egész szám
  "temps": [3.1415, 1.618, 2.718]      // legalább 1 elemű tömb, elemei mind számok
}
```

- Válasz hitelesítetlen kérés esetén: `401 UNAUTHORIZED`
- Válasz formailag helytelen kérés esetén: `422 UNPROCESSABLE CONTENT`
- Válasz helyes kérés esetén: `201 CREATED`
```json
[
  {
    "type": "cloudy",
    "logged_at": "2023-05-31 08:30:00",
    "temp": 3.1415,
    "location_id": 2,
    "updated_at": "2024-12-16T11:24:31.000000Z",
    "created_at": "2024-12-16T11:24:31.000000Z",
    "id": 197
  },
  {
    "type": "cloudy",
    "logged_at": "2023-05-31 08:45:00",
    "temp": 1.618,
    "location_id": 2,
    "updated_at": "2024-12-16T11:24:31.000000Z",
    "created_at": "2024-12-16T11:24:31.000000Z",
    "id": 198
  },
  {
    "type": "cloudy",
    "logged_at": "2023-05-31 09:00:00",
    "temp": 2.718,
    "location_id": 2,
    "updated_at": "2024-12-16T11:24:31.000000Z",
    "created_at": "2024-12-16T11:24:31.000000Z",
    "id": 199
  }
]
```

### II. rész: GraphQL (20 pont, min. 8 pont elérése szükséges!)

Ezekhez a feladatokhoz a kiindulási sémát megadtuk a `graphql\schema.graphql` fájlban. A feladatok legtöbbjét (de nem mindegyiket) meg lehet írni az adott séma Lighthouse direktívákkal való kiegészítésével, de természetesen külön írt rezolverrel is elfogadjuk a megoldásokat.

#### 8. feladat: `Query.locations` és `Query.weather` (2 pont)

Minden mérőhely és minden időjárásadat elemi mezőinek lekérése.

Kérés:
```graphql
query {
  locations {
    id
    name
    email
    lat
    lon
    public
    created_at
    updated_at
  }
  weather {
    id
    type
    location_id
    temp
    created_at
    updated_at
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
        "name": "non aut veniam",
        "email": "roscoe25@example.com",
        "lat": -58.9201,
        "lon": 75.2172,
        "public": true,
        "created_at": "2024-12-16 10:48:03",
        "updated_at": "2024-12-16 10:48:03"
      },
      stb.
    ],
    "weather": [
      {
        "id": "116",
        "type": "thunder",
        "location_id": "10",
        "temp": 29.7,
        "created_at": "2024-12-16 10:48:04",
        "updated_at": "2024-12-16 10:48:04"
      },
      stb.
    ]
  }
}
```

#### 9. feladat: `Query.location` (1 pont)

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
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "location": {
      "id": "4",
      "name": "vero veritatis est",
      "lat": 29.43253,
      "lon": 100.12515,
      "public": true
    }
  }
}
```

#### 10. feladat: `Weather.location` (1 pont)

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
        "type": "other",
        "location": {
          "id": "1",
          "name": "non aut veniam",
          "lat": -58.9201,
          "lon": 75.2172
        }
      },
      stb.
    ]
  }
}
```

#### 11. feladat: `Mutation.createWeather` (3 pont)

Új időjárásmérési adat felvétele. Siker esetén visszaadja a létrejött bejegyzést, különben `null` értéket kap.

A bemenő adatoknak adj meg a sémában egy `CreateWeatherInput` definiciót, amely a modellben tárolt mezőket várja az automatikusan kitöltődő `id`, `created_at` és `updated_at_` kivételével!

```graphql
mutation {
  createWeather(input: {
    type: "sunny"
    location_id: 1
    temp: 22.4
    logged_at: "1996-07-09 10:25:00"
  }) { 
    id
    type
    location_id
    temp
    logged_at
    created_at
    updated_at
  }
}
```

Mintaválasz:
```json
{
  "data": {
    "createWeather": {
      "id": "208",
      "type": "sunny",
      "location_id": "1",
      "temp": 22.4,
      "logged_at": "1996-07-09 10:25:00",
      "created_at": "2024-12-16 11:59:30",
      "updated_at": "2024-12-16 11:59:30"
    }
  }
}
```

#### 12. feladat: `Weather.warnings` (2 pont)

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
        "type": "other",
        "warnings": []
      },
      {
        "id": "2",
        "type": "cloudy",
        "warnings": [
          {
            "id": "1",
            "level": 3,
            "message": "ut"
          },
          stb.
        ]
      },
      stb.
    ]
  }
}
```

#### 13. feladat: `Location.currentTemp` (3 pont)

Legyen lehetőség a mérőhelyek felől indulva lekérni azt, hogy az adott mérőhelyhez tartozó legfrissebb bejegyzés (`logged_at` mező legnagyobb) szerint mennyi ott a hőmérséklet. Ha egy mérőhelyhez nem tartozik még időjárási adat, akkor `null` legyen az eredmény!

Technikai segítség új mező létrehozásához: `php artisan lighthouse:field`

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

#### 14. feladat: `Mutation.setPublic` (3 pont)

Ezzel a mutációval módosítani lehet a `public` mezőjét egy megadott azonosítójú mérőhelynek.

Az eredmény minden esetben egy string legyen az alábbiak szerint:
- `NOT FOUND` - ha nem létezik mérőhely a megadott azonosítóval
- `ALREADY SET` - ha a mérőhely public mezője már eleve a kivánt értékkel szerepelt
- `CHANGED` - ha a mérőhely public mezője most került megváltoztatásra

Kérés:
```graphql
mutation {
  setPublic(location_id: 3, public: false)
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

#### 15. feladat: `Query.statistics` (5 pont)

Statisztika készítése, amely a következő mezőket tartalmazza:

- `locationCount`: a mérőhelyek darabszáma (1 pont)
- `averageTemp`: a globális átlaghőmérséklet az összes időjárásmérési adat alapján (2 pont)
- `over30Celsius`: azon időjárásmérési adatok darabszáma, ahol a hőmérséklet nagyobb mint 30 °C (2 pont)

(Feltételezhető, hogy a statisztika készítésekor mindig van legalább egy időjárásadat az adatbázisban.)

Kérés:
```graphql
query {
  statistics {
    locationCount
    averageTemp
    over30Celsius
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
      "over30Celsius": 13
    }
  }
}
```