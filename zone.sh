#!/bin/bash

# Cesta k hlavní složce, kde začít hledat
directory="/home/david/github-classroom/ossp-cz/Nette"

# Najít a odstranit všechny soubory s příponou .ZoneIdentifier (rekurzivně)
find "$directory" -type f -name "*:Zone.Identifier" -exec rm -f {} \;

echo "Všechny .ZoneIdentifier soubory byly odstraněny ze složky $directory a všech jejích podsložek."
