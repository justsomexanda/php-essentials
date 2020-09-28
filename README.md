# Essentials class
This is a static class I use for every project, thats why I called it essentials

## Public functions
### noInject
Takes a **string** or a **json** and escapes critical characters. If a json is given it does the same, but only for each item and unlimited depth.

``` 
  echo Essentials::noInject("I'm a <critical> string")
  //Output: I\'m a &lt;critical&gt; string
  //Output as HTML: I'm a <critical> string
```

### getUserIpAddr
Gets the current public user IP-address

``` 
  echo Essentials::getUserIpAddr()
  //Output: 17.239.209.218
```

### isJson
Takes a string and checks if it is a json. Returns a bool

``` 
  echo Essentials::isJson("Hello world!")
  //Output: false
```
