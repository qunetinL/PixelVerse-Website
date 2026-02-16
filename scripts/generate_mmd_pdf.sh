#!/bin/bash

# Script de conversion des fichiers Mermaid (.mmd) en PDF
# Nécessite Node.js et npx

# Chemins
MMD_DIR="docs/Diagrammes"
PDF_DIR="docs/Pdf"

# Créer le dossier PDF s'il n'existe pas
mkdir -p "$PDF_DIR"

echo "🚀 Début de la conversion des diagrammes Mermaid..."

# Parcourir tous les fichiers .mmd
for file in "$MMD_DIR"/*.mmd; do
    if [ -f "$file" ]; then
        filename=$(basename -- "$file")
        basename="${filename%.*}"
        
        echo "  ⏳ Conversion de $filename ..."
        
        # Utiliser mmdc pour convertir en PDF
        # On utilise -y pour npx pour éviter les prompts
        npx -y @mermaid-js/mermaid-cli -i "$file" -o "$PDF_DIR/$basename.pdf" > /dev/null 2>&1
        
        if [ $? -eq 0 ]; then
            echo "  ✔ $basename.pdf généré."
        else
            echo "  ❌ Erreur lors de la conversion de $filename."
        fi
    fi
done

echo "✨ Terminé ! Vos diagrammes sont dans le dossier $PDF_DIR."
