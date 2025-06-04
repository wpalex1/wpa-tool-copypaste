<?php
/**
 * Plugin Name: WP Alex - Copy/Paste Helper
 * Description: Affiche dans "Outils" le contenu .htaccess et wp-config.php optimisé, générique, ultra commenté, prêt à copier-coller pour WordPress.
 * Version: 1.9
 * Author: Alexandre Trocmé
 * Author URI: https://wpalex.fr
 */

if (!defined('ABSPATH')) exit;

// Ajoute le menu dans Outils
add_action('admin_menu', function() {
    add_management_page(
        'WP Alex - Copy/Paste : htaccess & wp-config.php',
        'WPA Copy/Paste',
        'manage_options',
        'wpa-copypaste',
        'wpa_copypaste_page'
    );
});

// Ajoute le lien "Consulter" dans la liste des plugins
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $url = admin_url('tools.php?page=wpa-copypaste');
    array_unshift($links, '<a href="' . esc_url($url) . '">Consulter</a>');
    return $links;
});

function wpa_copypaste_page() { ?>
    <style>
        .wpa-tabs { margin-bottom: 1.2em; border-bottom: 1px solid #eee; display: flex; gap: 0.5em; }
        .wpa-tabs a { display:inline-block; padding:8px 22px; border:1px solid #ddd; border-bottom:none; background:#f8f8f8; color:#333; text-decoration:none; border-radius:8px 8px 0 0; font-size:1.1em;}
        .wpa-tabs a.active { background:#fff; border-bottom:1px solid #fff; font-weight:bold; color:#2271b1;}
        .wpa-panel { display:none; background:#fff; border:1px solid #ddd; border-radius:0 8px 8px 8px; padding:24px 24px 12px 24px; }
        .wpa-panel.active { display:block; }
        @media (max-width:800px) {
            .wpa-panels-flex { flex-direction:column; gap:16px !important;}
        }
        .wpa-contact {margin:2.2em 0 0.7em 0;text-align:left;color:#555;font-size:1.09em;font-weight:bold;}
        .wpa-contact a {text-decoration:none;color:#2271b1;}
        .wpa-contact a:hover {text-decoration:underline;}
        .wpa-tip {margin:2em 0 0 0;font-size:1.08em;text-align:left;}
    </style>
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            const tabs = document.querySelectorAll('.wpa-tabs a');
            const panels = document.querySelectorAll('.wpa-panel');
            tabs.forEach(tab=>{
                tab.addEventListener('click',function(e){
                    e.preventDefault();
                    tabs.forEach(t=>t.classList.remove('active'));
                    panels.forEach(p=>p.classList.remove('active'));
                    tab.classList.add('active');
                    document.getElementById(tab.dataset.panel).classList.add('active');
                });
            });
            // Afficher le premier onglet par défaut
            if(document.querySelector('.wpa-tabs a.active')===null && tabs.length) {
                tabs[0].classList.add('active');
                panels[0].classList.add('active');
            }
        });
    </script>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:.5em;font-size:1.5em;">
            <span style="font-size:2em;">📋</span>
            WP Alex - Copy/Paste : htaccess &amp; wp-config.php
        </h1>
        <div class="wpa-tabs">
            <a href="#" data-panel="wpa-tab-htaccess" class="active">📂 .htaccess</a>
            <a href="#" data-panel="wpa-tab-wpconfig">⚙️ wp-config.php</a>
        </div>
        <div class="wpa-panels-flex" style="display:flex;gap:32px;flex-wrap:wrap;">
            <div class="wpa-panel active" id="wpa-tab-htaccess" style="flex:1 1 420px;min-width:350px;max-width:640px">
                <h2 style="display:flex;align-items:center;gap:.5em;">
                    <span style="font-size:1.6em;">📂</span> .htaccess (WordPress générique)
                </h2>
                <textarea style="width:100%;height:540px;font-family:monospace;font-size:1em;padding:.7em;background:#f8fafe;border:1px solid #bcdff7;border-radius:8px;" readonly onclick="this.select()"><?php echo htmlspecialchars(wpa_copypaste_htaccess(), ENT_QUOTES); ?></textarea>
            </div>
            <div class="wpa-panel" id="wpa-tab-wpconfig" style="flex:1 1 420px;min-width:350px;max-width:640px">
                <h2 style="display:flex;align-items:center;gap:.5em;">
                    <span style="font-size:1.6em;">⚙️</span> wp-config.php (options avancées)
                </h2>
                <textarea style="width:100%;height:540px;font-family:monospace;font-size:1em;padding:.7em;background:#f8fafe;border:1px solid #bcdff7;border-radius:8px;" readonly onclick="this.select()"><?php echo htmlspecialchars(wpa_copypaste_wpconfig(), ENT_QUOTES); ?></textarea>
            </div>
        </div>
        <div class="wpa-tip">
            <span style="font-size:1.1em;">💡</span>
            <b>Astuce</b> : Clique dans une zone puis <b>Ctrl+A</b> & <b>Ctrl+C</b> pour tout copier !
        </div>
        <div class="wpa-contact">
            <span style="font-size:1.15em;">👤</span>
            <a href="https://wpalex.fr" target="_blank" rel="noopener noreferrer">Alexandre Trocmé – wpalex.fr</a>
        </div>
    </div>
<?php
}

// Contenu htaccess avec emojis en commentaire pour chaque bloc, + exemple redirection 301 commentée
function wpa_copypaste_htaccess() {
return <<<HTACCESS
# =========================================================
# 📂 .htaccess WordPress – Sécurité, Performance & Optimisations
# Environnement : o2switch / Blocksy / Elementor / Gutenberg / mutualisé
# Dernière révision : 2025-06
# =========================================================

# ———————————— 🔥 Pagespeed désactivé (stabilité visuelle WP)
<IfModule pagespeed_module>
  ModPagespeed off
</IfModule>


# ———————————————— 🔐 SÉCURITÉ ————————————————

# 🚫 Désactive XML-RPC (API obsolète sauf cas spécifiques)
<Files xmlrpc.php>
  Order Deny,Allow
  Deny from all
</Files>

# 🚫 Bloque accès aux fichiers sensibles
<FilesMatch "^(readme|changelog|debug|license)\.(txt|md|log|html?)$">
  Deny from all
</FilesMatch>

# 🔍 Force une 404 si readme.html est demandé
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^readme\.html$ - [R=404,L,NC]
</IfModule>

# 🕵️‍♂️ Anti-disclosure PHP (protection scans de debug)
<IfModule mod_rewrite.c>
  RewriteCond %{QUERY_STRING} \=PHP[0-9a-f\-]{36} [NC]
  RewriteRule .* - [F]
</IfModule>

# 🚫 Interdiction du listing des répertoires
<IfModule mod_autoindex.c>
  Options -Indexes
</IfModule>

# 👥 Bloque l'énumération des auteurs WordPress
<IfModule mod_rewrite.c>
  RewriteCond %{QUERY_STRING} ^author=([0-9]+)
  RewriteRule .* - [F,L]
</IfModule>

# 🛡️ Bloque le spam basique sur les commentaires
<IfModule mod_rewrite.c>
  RewriteCond %{REQUEST_METHOD} POST
  RewriteCond %{REQUEST_URI} .wp-comments-post\.php*
  RewriteCond %{HTTP_USER_AGENT} ^$
  RewriteRule .* - [F,L]
</IfModule>

# 🔒 Interdiction de téléchargement de fichiers SQL de backup
<FilesMatch "\.(sql|sql\.gz|sql\.zip)$">
  Deny from all
</FilesMatch>

# 🧱 Sécurise fichiers de config sensibles
<FilesMatch "\.(bak|config|env|ini|log|sh|inc|swp|dist|psd)$">
  Require all denied
</FilesMatch>


# ——————————————— 🌍 REDIRECTIONS & HTTPS ———————————————

# 🔒 Force HTTPS (si pas déjà activé côté serveur)
<IfModule mod_rewrite.c>
  RewriteCond %{HTTPS} !=on
  RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# 🌐 Redirige www vers non-www (modifier selon besoin)
RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
RewriteRule ^(.*)$ https://%1/$1 [R=301,L]

# ———— Exemples de redirections 301 personnalisées ————

# 🔁 Rediriger une ancienne page interne vers la nouvelle URL interne
# Redirect 301 /ancienne-page/ /nouvelle-page/

# 🔁 Rediriger une ancienne page vers une URL externe (site tiers)
# Redirect 301 /ancienne-page-externe/ https://exemple.com/nouvelle-page/

# 🔁 Rediriger tout un dossier/chemin
# Redirect 301 /blog-archive/ /actualites/

# 🔁 Rediriger plusieurs anciennes URLs vers la même page
# Redirect 301 /ancien-produit-1/ /nouveau-produit/
# Redirect 301 /ancien-produit-2/ /nouveau-produit/

# 🔁 Rediriger une ancienne catégorie vers une nouvelle (WordPress)
# Redirect 301 /category/ancienne-categorie/ /category/nouvelle-categorie/

# 🔁 Rediriger un fichier précis (exemple : PDF déplacé)
# Redirect 301 /docs/ancien-fichier.pdf /docs/nouveau-fichier.pdf


# ——————————————— 🛡️ HEADERS HTTP DE SÉCURITÉ ———————————————

<IfModule mod_headers.c>
  Header always set Strict-Transport-Security "max-age=31536000" env=HTTPS
  Header set X-XSS-Protection "1; mode=block"
  Header set X-Content-Type-Options "nosniff"
  Header always append X-Frame-Options SAMEORIGIN
  Header set Referrer-Policy "strict-origin-when-cross-origin"
  Header set Feature-Policy "geolocation 'none'; midi 'none'; notifications 'none'; push 'none'; sync-xhr 'none'; microphone 'none'; camera 'none'; magnetometer 'none'; gyroscope 'none'; speaker 'none'; vibrate 'none'; fullscreen 'self'; payment 'none';"
</IfModule>


# ——————————————— ⚡️ OPTIMISATIONS & CACHE ———————————————

# ✅ Encodage par défaut
AddDefaultCharset UTF-8

<IfModule mod_mime.c>
  AddCharset UTF-8 .atom .css .js .json .rss .vtt .xml
  AddType image/avif avif
  AddType image/avif-sequence avifs
</IfModule>

# ❌ Désactive les ETags (évite bugs cache CDN ou navigateur)
<IfModule mod_headers.c>
  Header unset ETag
</IfModule>
FileETag None

# ⏳ Cache navigateur optimisé (par type de fichier)
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresDefault "access plus 1 month"
  ExpiresByType text/html "access plus 0 seconds"
  ExpiresByType application/json "access plus 0 seconds"
  ExpiresByType text/xml "access plus 0 seconds"
  ExpiresByType application/xml "access plus 0 seconds"
  ExpiresByType application/rss+xml "access plus 1 hour"
  ExpiresByType application/atom+xml "access plus 1 hour"
  ExpiresByType image/x-icon "access plus 1 week"
  ExpiresByType image/gif "access plus 4 months"
  ExpiresByType image/png "access plus 4 months"
  ExpiresByType image/jpeg "access plus 4 months"
  ExpiresByType image/webp "access plus 4 months"
  ExpiresByType video/ogg "access plus 4 months"
  ExpiresByType audio/ogg "access plus 4 months"
  ExpiresByType video/mp4 "access plus 4 months"
  ExpiresByType video/webm "access plus 4 months"
  ExpiresByType image/avif "access plus 4 months"
  ExpiresByType image/avif-sequence "access plus 4 months"
  ExpiresByType text/css "access plus 1 year"
  ExpiresByType application/javascript "access plus 1 year"
  ExpiresByType application/x-javascript "access plus 1 year"
  ExpiresByType font/ttf "access plus 4 months"
  ExpiresByType font/otf "access plus 4 months"
  ExpiresByType font/woff "access plus 4 months"
  ExpiresByType font/woff2 "access plus 4 months"
  ExpiresByType image/svg+xml "access plus 4 months"
</IfModule>

# 📦 Compression GZIP optimisée
<IfModule mod_deflate.c>
  SetOutputFilter DEFLATE
  <IfModule mod_setenvif.c>
    <IfModule mod_headers.c>
      SetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s*,?\s*)+|[X~-]{4,13}$ HAVE_Accept-Encoding
      RequestHeader append Accept-Encoding "gzip,deflate" env=HAVE_Accept-Encoding
      SetEnvIfNoCase Request_URI \.(?:gif|jpe?g|png|rar|zip|exe|flv|mov|wma|mp3|avi|swf|mp?g|mp4|webm|webp|pdf)$ no-gzip dont-vary
    </IfModule>
  </IfModule>
  <IfModule mod_filter.c>
    AddOutputFilterByType DEFLATE application/atom+xml application/javascript application/json application/rss+xml application/vnd.ms-fontobject application/x-font-ttf application/xhtml+xml application/xml font/opentype image/svg+xml image/x-icon text/css text/html text/plain text/x-component text/xml
  </IfModule>
  <IfModule mod_headers.c>
    Header append Vary: Accept-Encoding
  </IfModule>
</IfModule>


# ——————————————— 🔄 RÈGLES WORDPRESS ———————————————

# ⚠️ Ne pas modifier cette section sans savoir ce que vous faites !
# BEGIN WordPress
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
  RewriteBase /
  RewriteRule ^index\.php$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.php [L]
</IfModule>
# END WordPress
HTACCESS;
}

function wpa_copypaste_wpconfig() {
return <<<WPCONFIG
<?php
/** =========================================================
 * ⚙️ wp-config.php – options sécurité & perf WordPress
 * WPAlex – https://github.com/wpalex
 * Place APRÈS la config DB, AVANT /* That's all... */
 ========================================================= */

/** --------- 🛡️ Sécurité --------- */
define('DB_COLLATE', 'utf8mb4_unicode_ci'); // Collation recommandée
define('DISALLOW_FILE_EDIT', true);        // Bloque l'éditeur de fichiers dans l'admin
define('ALLOW_UNFILTERED_UPLOADS', false); // Bloque les uploads non filtrés
define('RELOCATE', false);                 // Désactive la relocalisation automatique
define('WP_ALLOW_REPAIR', false);          // Désactive la réparation auto BDD
define('WP_AUTO_UPDATE_CORE', 'minor');    // Màj auto mineures (true pour tout)

/** --------- 🧪 Debug (production=false partout) --------- */
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', false);
define('DIEONDBERROR', false);

/** --------- 🧠 Mémoire PHP --------- */
define('WP_MEMORY_LIMIT', '256M');      // Limite mémoire WP (augmentez si besoin)
define('WP_MAX_MEMORY_LIMIT', '256M');  // Limite mémoire WP admin

/** --------- 🚀 Performances --------- */
define('CONCATENATE_SCRIPTS', true);   // Combine JS en admin
define('WP_POST_REVISIONS', 5);        // Max 5 révisions par page/article
define('AUTOSAVE_INTERVAL', 180);      // Autosave toutes les 3 min
define('EMPTY_TRASH_DAYS', 10);        // Corbeille vidée au bout de 10 jours
define('IMAGE_EDIT_OVERWRITE', true);  // Évite les doublons d'images
define('WP_CACHE', true);              // Active le cache si plugin présent

/** --------- 🔄 Mises à jour --------- */
define('WP_AUTO_UPDATE_CORE', true); // Màj auto activées (mineures + majeures)
/*
define('AUTOMATIC_UPDATER_DISABLED', false); // Màj auto plugins/thèmes
*/

/** --------- 🔐 SSL obligatoire dans l’admin --------- */
define('FORCE_SSL_ADMIN', true);

/** --------- 💾 Redis (optionnel, adapter si besoin) --------- */
/*
define('WP_REDIS_CLIENT', 'phpredis');
define('WP_REDIS_SCHEME', 'unix');
define('WP_REDIS_PATH', '/chemin/vers/redis.sock');
define('WP_REDIS_PASSWORD', 'votre_mot_de_passe');
define('WP_REDIS_MAXTTL', 3600);
*/
WPCONFIG;
}
