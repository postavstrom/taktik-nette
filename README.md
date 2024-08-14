# Nette Application

## Přehled

Tento projekt je aplikace vyvinutá v PHP s použitím Nette frameworku. Tento návod vám pomůže s instalací a spuštěním aplikace, jakož i s testováním.
## Požadavky

Před instalací aplikace se ujistěte, že máte nainstalovány následující nástroje:

- [PHP](https://www.php.net/) (verze 7.4 nebo vyšší)
- [Composer](https://getcomposer.org/)
- [MySQL](https://www.mysql.com/) (nebo jiná databáze, kterou používáte)
- [Nette Framework](https://nette.org/)

## Instalace

1. **Klonování repozitáře**

   Nejprve si klonujte repozitář:

   ```bash
   git clone https://github.com/postavstrom/taktik-nette.git
   ```

   Přejděte do adresáře s projektem:

   ```bash
   cd taktik-nette
   ```

2. **Instalace závislostí**

   Instalujte PHP závislosti pomocí Composeru:

   ```bash
   composer install
   ```

3. **Inicializace databáze**

   Pro vytvoření potřebných tabulek v databázi spusťte následující SQL skript:

   ```bash
   CREATE TABLE survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    comments TEXT,
    agree_to_terms BOOLEAN NOT NULL,
    interests TEXT);
   ```

   Upravte soubor config/common.neon a zadejte správné údaje pro připojení k vaší databázi.

4. **Spuštění aplikace**

   Spusťte vestavěný PHP server:

   ```bash
   php -S localhost:8000 -t www
   ```
   Aplikaci si můžete otevřít v prohlížeči na http://localhost:8000.

5. **Testování**

   Projekt používá Nette Tester. Ujistěte se, že máte nainstalovány všechny závislosti a spusťte test následujícím příkazem:

   ```bash
   php artisan serve
   ```

6. **Testování aplikace**

   Aplikace obsahuje testy, které můžete spustit pomocí PHPUnit. Pro spuštění testů použijte:

   ```bash
   vendor/bin/tester tests
   ```