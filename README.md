# SEGUR_PROIEKTUA

## Partaideak:
- Maider Tato
- Olatz Elejalde
- Mikel Eguia
- Asier las Hayas
- Lucia del Rio
- Ainhoa Tomas

## Nola hedatu:
1. Proiektua klonatu
   ```bash
   $ git clone git@github.com:LUC1A05/SEGUR_PROIEKTUA.git
   $ cd SEGUR_PROIEKTUA
   $ git checkout entrega_1
   ```
3. Docker irudia sortu
   ```bash
   docker image rm web:latest
   docker build -t="web" .
   ```
5. Kontainerraren exekuzioa
   ```bash
   docker-compose up -d
   ```
6. Kontainerrak martxan daudela egiaztatu
   ```bash
   docker ps
   ```
7. Datu basea inportatu:
  - localhost:8890 atzitu
  - Sesioa hasi:
     user: admin
     pass:test
  - databese atzitu
  - init.sql fitxategia inportatu
