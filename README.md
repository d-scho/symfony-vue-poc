# Symfony Vue POC

The idea is to serve a Vue SPA via a specially designed Symfony controller.

The thoughts behind that are
- independence of Symfony-specific tooling for incorporating Vue into Symfony
- that means for example, build via vite (at the moment, WebpackEncore is
recommended by the Symfony UX package)
- obscuring used frontend technology (not demonstrated currently, but one could
make only a Symfony-controlled login page available for unauthenticated users and
guard all app and API routes)
