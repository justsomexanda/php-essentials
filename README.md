# Essentials class
This is a static class I use for every project, thats why I called it essentials

## Public functions
### noInject
Takes a **string** or a **json** and escapes critical characters. If a json is given it does the same, but only for each item and unlimited depth.

``` 
  echo Essentials::noInject("I'm a <critical> string");
  //Output: I'm&nbsp;a&nbsp;&lt;critical&gt;&nbsp;string
  //Output as HTML: I'm a <critical> string
```

### getUserIpAddr
Gets the current public user IP-address

``` 
  echo Essentials::getUserIpAddr();
  //Output: 17.239.209.218
```

### isJson
Takes a string and checks if it is a json. Returns a bool

``` 
  echo Essentials::isJson("Hello world!");
  //Output: false
```

### realBreak
Detects a newline in different forms and returns either html or other newline-formats

``` 
  echo Essentials::realBreak("Hey <br> a \\n b \\r c");
  //Output: Hey <br> a <br> b <br> c
```

### progress_bar
Prints a simple progressbar. I added this code from: https://gist.github.com/mayconbordin/2860547

``` 
  echo Essentials::progress_bar(10, 100, "Downloading wordpress", 10);
  //Output: 10%[=>         ]Downloading wordpress
```

# SQL
This is my sql-class which requires essentials.class.php

## Public functions
### construct
Requires (server,user,password,database) for instantiation

``` 
  $sql = new Sql('localhost','user','somethingsafeprobably','database');
```

### read
Query a table and/or return a $column from $where $is

``` 
  //Query a table for later (faster) use. This line is optional
  $sql->read('persons');
  
  //Return column
  echo $sql->read('persons','name','uid','a6a2f5842');
  //Output: ["Alexander","Blasl"]
```

### update
Update an existing column (table,col,where,is,newdata)

``` 
  $sql->update("persons","name","uid","a6a2f5842","[\"Benjamin\",\"Buttons\"]");
```

