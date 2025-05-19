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
# 📂 .htaccess générique WordPress – sécurité & optimisation
# WPAlex – https://github.com/wpalex
# =========================================================

# 👤 Bloque l'énumération d'auteurs (évite la découverte d'utilisateurs)
<IfModule mod_rewrite.c>
    RewriteCond %{QUERY_STRING} ^author=([0-9]+)
    RewriteRule .* - [F,L]
</IfModule>

# 💾 Interdit le téléchargement de sauvegardes SQL
<FilesMatch "\\.(sql.gz|sql.zip|sql)\$">
    Deny from all
</FilesMatch>

# 📑 Bloque l'accès aux fichiers readme et license
<FilesMatch "readme.html|license.txt">
    Deny from all
</FilesMatch>

# 🚫 Bloque l'accès à xmlrpc.php (si non utilisé)
<Files xmlrpc.php>
    Order Deny,Allow
    Deny from all
</Files>

# 🪵 Bloque la lecture des fichiers error_log
<FilesMatch "error_log">
    Deny from all
</FilesMatch>

# 🗨️ Protection anti-spam basique sur commentaires (adapter domaine si besoin)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_METHOD} POST
    RewriteCond %{REQUEST_URI} .wp-comments-post\.php*
    # RewriteCond %{HTTP_REFERER} !example.com.* [OR]
    RewriteCond %{HTTP_USER_AGENT} ^\$
    RewriteRule .* - [F,L]
</IfModule>

# 🗂️ Désactive le listing des dossiers
Options All -Indexes

# 🔒 Force le HTTPS sur tout le site (adaptez pour votre hébergeur si besoin)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# 🌐 Redirection www vers non-www (adapter selon votre préférence)
RewriteCond %{HTTP_HOST} ^www\.(.+)\$ [NC]
RewriteRule ^(.*)\$ https://%1/\$1 [R=301,L]

# 🔁 Exemple de redirection 301 personnalisée
# -----------------------------------------------
# 🔗 Redirige l'ancienne page "/ancienne-page/" vers la nouvelle "/nouvelle-page/"
Redirect 301 /ancienne-page/ /nouvelle-page/
# 🔗 Pour une cible externe :
# Redirect 301 /ancienne-page/ https://exemple.com/nouvelle-page/
# -----------------------------------------------

# 🛡️ Active certains headers de sécurité (adaptez selon vos plugins/themes)
<IfModule mod_headers.c>
    Header set Permissions-Policy "geolocation=(), camera=(), microphone=(), fullscreen=(self)"
    Header always set Strict-Transport-Security "max-age=31536000" env=HTTPS
    Header set X-XSS-Protection "1; mode=block"
    Header set X-Content-Type-Options "nosniff"
    Header always append X-Frame-Options SAMEORIGIN
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self';"
    Header set Feature-Policy "geolocation 'none'; midi 'none'; notifications 'none'; push 'none'; sync-xhr 'none'; microphone 'none'; camera 'none'; magnetometer 'none'; gyroscope 'none'; speaker 'none'; vibrate 'none'; fullscreen 'self'; payment 'none';"
</IfModule>

# ⏳ Expire headers – met en cache les ressources statiques
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
    ExpiresByType text/html "access plus 0 seconds"
    ExpiresByType application/json "access plus 0 seconds"
    ExpiresByType text/xml "access plus 0 seconds"
    ExpiresByType image/jpg "access plus 6 months"
    ExpiresByType image/jpeg "access plus 6 months"
    ExpiresByType image/png "access plus 6 months"
    ExpiresByType image/gif "access plus 6 months"
    ExpiresByType image/webp "access plus 6 months"
    ExpiresByType image/svg+xml "access plus 6 months"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
</IfModule>

# 🗜️ Compression GZIP (si supporté par l'hébergeur)
<IfModule mod_filter.c>
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/plain text/html text/xml text/css
        AddOutputFilterByType DEFLATE application/xml application/xhtml+xml application/rss+xml application/javascript application/x-javascript
        AddOutputFilterByType DEFLATE font/ttf font/otf font/woff image/svg+xml image/x-icon
        <IfModule mod_headers.c>
            Header append Vary User-Agent
        </IfModule>
        BrowserMatch ^Mozilla/4 gzip-only-text/html
        BrowserMatch ^Mozilla/4\.0[678] no-gzip
        BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
    </IfModule>
</IfModule>

# =============== Règles natives WordPress ===============
# 📝 Ne pas modifier cette section !
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
