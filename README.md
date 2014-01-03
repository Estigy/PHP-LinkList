# PHP-LinkList #

## Was ist das hier? ##

PHP-LinkList bietet eine einfache, aber solide Basis für Link-Verzeichnisse aller Art.

Das Frontend bietet verschachtelte Kategorien, Link-Listen mit Detailansicht und Werbungs-Einbindung (derzeit Google Adsense). User können neue Links einschicken, welche vom Administrator freigeschaltet werden müssen.
Im Backend können die Kategorien, Links, und Admin-User verwaltet werden.

## Woraus besteht der Code?

Das Projekt basiert im wesentlichen auf 3 Teilen:

- Zend Framework 1 für den PHP-Teil
- jQuery 2 als JS-Grundlage
- Bootstrap 3 für das Layout

Dadurch sollte sich die Einarbeitungszeit in engen Grenzen halten.

## Konfiguration

Im File `localConfig.php` können die wichtigsten Parameter (Zugangsdaten, E-Mail-Adressen, projektspezifische Texte) leicht bearbeitet werden.

Das Einbinden eigener CSS-Files ist dort ebenfalls vorgesehen. Auch die IDs für die Werbung sind dort gespeichert.
