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
   git clone git@github.com:LUC1A05/SEGUR_PROIEKTUA.git
   cd SEGUR_PROIEKTUA
   git checkout entrega_1
3. Docker irudia sortu
   docker image rm web:latest
   docker build -t="web" .
5. Kontainerraren exekuzioa
   docker-compose up -d
6. Kontainerrak martxan daudela egiaztatu
   docker ps
7. Datu basea inportatu:
  1. localhost:8890 atzitu
  2. Sesioa hasi:
     user: admin
     pass:test
  3. databese atzitu
  4. database.sql fitxategia inportatu
