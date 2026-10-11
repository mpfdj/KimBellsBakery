Er zijn twee veelgebruikte manieren om YAML naar een associatieve array in PHP te vertalen: met de **native `yaml_parse()` functie** (via de PECL-extensie) of met de populaire **Symfony YAML-component** (via Composer).

### 🐘 Optie 1: De native `yaml_parse()` functie (PECL-extensie)

Dit is de snelste methode, maar vereist dat de **`yaml` PECL-extensie** is geïnstalleerd op je server.

**Installatie:**
```bash
pecl install yaml
```
Voeg daarna `extension=yaml.so` toe aan je `php.ini`.

**Gebruik:**
```php
<?php
$yaml = "
gebruiker:
  naam: Kim
  leeftijd: 28
  email: kim@example.com
  adres:
    straat: Hoofdstraat 1
    stad: Amsterdam
";

$data = yaml_parse($yaml);

// $data is nu een associatieve array
echo $data['gebruiker']['naam'];            // Kim
echo $data['gebruiker']['adres']['stad'];   // Amsterdam

// Of lees direct uit een bestand
$data = yaml_parse_file('config.yaml');
```

### 🎼 Optie 2: De Symfony YAML-component (Composer, aanbevolen)

Dit is de **meest gebruikte en aanbevolen** methode. Het werkt op vrijwel elke PHP-omgeving en heeft geen speciale extensies nodig.

**Installatie:**
```bash
composer require symfony/yaml
```

**Gebruik:**
```php
<?php
require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// 1. YAML-string parsen
$yaml = "
gebruiker:
  naam: Kim
  leeftijd: 28
  email: kim@example.com
  adres:
    straat: Hoofdstraat 1
    stad: Amsterdam
";

$data = Yaml::parse($yaml);
// of: $data = Yaml::parseFile('config.yaml');

echo $data['gebruiker']['naam'];            // Kim
echo $data['gebruiker']['adres']['stad'];   // Amsterdam

// 2. Array terug naar YAML
$yamlOutput = Yaml::dump($data, 4, 2);
echo $yamlOutput;
```

### ⚖️ Vergelijking

| Kenmerk | `yaml_parse()` (PECL) | Symfony YAML (Composer) |
| :--- | :--- | :--- |
| **Installatie** | PECL-extensie nodig | Composer (eenvoudig) |
| **Snelheid** | Zeer snel (C-implementatie) | Snel (PHP) |
| **Beschikbaarheid** | Niet altijd aanwezig | Overal via Composer |
| **Features** | Basis | Uitgebreid, goed onderhouden |
| **Aanbevolen voor** | Performance-kritieke servers | De meeste projecten |

### 💡 Belangrijke opties bij Symfony YAML

```php
use Symfony\Component\Yaml\Yaml;

// 1. Met parse-opties (bijv. voor datums en constanten)
$data = Yaml::parse($yaml, Yaml::PARSE_DATETIME | Yaml::PARSE_CONSTANT);

// 2. Bij fouten een exception
try {
    $data = Yaml::parseFile('config.yaml');
} catch (\Symfony\Component\Yaml\Exception\ParseException $e) {
    echo "YAML fout: " . $e->getMessage();
}

// 3. Array naar YAML met inspringing
$yaml = Yaml::dump($data, 4, 2); // diepte 4, 2 spaties inspringing
```

### 🎯 Conclusie

Voor de meeste projecten is de **Symfony YAML-component** de beste keuze: het is eenvoudig te installeren via Composer, werkt overal en is goed gedocumenteerd. Gebruik de native `yaml_parse()` alleen als je zeker weet dat de PECL-extensie beschikbaar is en je maximale performance nodig hebt.