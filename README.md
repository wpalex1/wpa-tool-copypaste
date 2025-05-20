# 📂 .htaccess WordPress – Sécurité, Performance & Optimisations

Ce fichier `.htaccess` est une version fusionnée et ultra commentée pour WordPress, adaptée aux hébergements mutualisés modernes (O2Switch, Infomaniak…), compatible avec les thèmes et constructeurs récents (Blocksy, Elementor, Gutenberg…).  
Il couvre : sécurité, cache, redirections, gzip, headers avancés, et bonnes pratiques.

---

## ✨ Fonctions principales

- **Sécurité** :  
  - Désactive XML-RPC
  - Bloque l’accès aux fichiers sensibles (`readme`, `changelog`, `debug`, `license`, `error_log`)
  - Anti-enumération d’auteurs
  - Anti-spam basique sur les commentaires
  - Interdit le téléchargement de sauvegardes SQL
  - Bloque certains scans PHP malveillants
  - Désactive le listing des dossiers

- **Performance & cache** :  
  - Force HTTPS et redirection www/non-www
  - Exemples de redirections 301 personnalisées
  - Headers de sécurité avancés (HSTS, CSP, X-Frame, etc.)
  - Expirations granulaire par type de ressource
  - Compression Gzip avancée
  - Activation des types MIME modernes (AVIF…)

- **Clarté et maintenance** :  
  - Sections clairement séparées avec emojis
  - Commentaires explicites pour chaque règle
  - Prêt à copier-coller, modifiable selon vos besoins

---

## 📑 Exemples d’utilisation

1. **Remplacez** le contenu du `.htaccess` à la racine de votre site WordPress par celui fourni.
2. **Adaptez** les sections de redirections (`301`) selon vos besoins.
3. **Testez** votre site. En cas de problème, vérifiez les erreurs d’Apache et désactivez temporairement certains blocs.

---

## 🛠️ Astuces & conseils

- Pour WooCommerce ou des besoins spécifiques (API, multisite…), adaptez le fichier ou demandez une version personnalisée.
- Ne dupliquez pas les règles natives WordPress (`# BEGIN WordPress ... # END WordPress`).
- Les headers de sécurité sont compatibles avec la majorité des thèmes et plugins courants.

---

## ℹ️ Auteurs & ressources

- Créé et maintenu par [Alexandre Trocmé – wpalex.fr](https://wpalex.fr)
- Inspiré des bonnes pratiques WordPress, SecuPress, WP Rocket, Mozilla Observatory, etc.

---

**Besoin d’une aide, version spéciale, ou suggestion ?**  
Contactez-moi sur [wpalex.fr](https://wpalex.fr) ou ouvrez une issue sur le dépôt GitHub associé.
