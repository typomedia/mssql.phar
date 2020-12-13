# mssql

Simple Microsoft SQL Console Client. Basic implementation of `sqlcmd` in pure PHP.

## Download

[mssql.phar](https://github.com/typomedia/mssql/raw/master/dist/mssql.phar)

## Usage
    php mssql.phar query [options] Executes a statement and returning the results...
    php mssql.phar exec  [options] Executes one or more statements without returning the results...

### Options
    -S, --host[=HOST]     Server host [default: "localhost"]
    -U, --user=USER       Username
    -P, --pass=PASS       Password
    -T, --port[=PORT]     Port [default: 1433]
    -Q, --query[=QUERY]   Query [default: "SELECT name FROM master.dbo.sysdatabases"]
    -F, --file[=FILE]     Input file

## Help

    php mssql.phar query --help
    php mssql.phar exec --help

## Query
    
    # default query shows all databases on localhost
    php mssql.phar query --user sa --pass ********
    
    mssql@1.1.0 by Typomedia Foundation, Philipp Speck
    +-------------+-------------------------------------+
    | demo_master | /var/opt/mssql/data/demo_master.mdf |
    | test_master | /var/opt/mssql/data/test_master.mdf |
    +-------------+-------------------------------------+
    (2 rows affected in 0 s)

    # specific query on specific host
    php mssql.phar query --host mssql.example.local --user sa --pass ******** --query "SELECT name FROM master.dbo.sysdatabases"
    
    # specific query with input file
    php mssql.phar query --host mssql.example.local --user sa --pass ******** --file query.sql 
    
## Exec
    
    # specific query on localhost
    php mssql.phar exec --user sa --pass ******** --query "CREATE TABLE demo_master.demo_table(Id int)"
    
    # specific query on specific host with input file
    php mssql.phar exec --host mssql.example.local --user sa --pass ******** --file exec.sql

---
© 2020 Typomedia Foundation. Created with ♥ in Heidelberg by Philipp Speck.
