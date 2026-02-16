#!/bin/bash

# Script de conversion des fichiers Markdown en PDF
# Nécessite Node.js et npx

# Chemins
MD_DIR="docs/Md"
PDF_DIR="docs/Pdf"

# Aller dans le dossier des MD
cd "$MD_DIR" || exit

echo "🚀 Début de la conversion des documents..."

# Convertir tous les fichiers .md en PDF
npx md-to-pdf *.md

# Déplacer les PDF générés vers le dossier destination
mv *.pdf "../../$PDF_DIR/"

echo "✨ Conversion terminée ! Vos PDF sont dans le dossier $PDF_DIR."
