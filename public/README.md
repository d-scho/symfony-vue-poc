The server has to be configured so that all
traffic to non-existing files or directories
in this `public` directory is redirected
to `index.php`.

An `.htaccess` example for this:

```apache
# Deny access to .htaccess
RewriteRule ^.htaccess$ - [F,L]

# Return existing files
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# Reroute everything else to index.php
RewriteRule ^(.*)$ /index.php/$1 [L]
```