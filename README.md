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
2. Docker irudia sortu
   docker build -t SEGUR_PROIEKTUA:latest .
3. Kontainerraren exekuzioa
   docker run -d -p 3000:3000 --name segur_proiektua_container SEGUR_PROIEKTUA:latest
4. Kontainerrak martxan daudela egiaztatu
   docker ps
