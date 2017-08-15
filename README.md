# Simple_OOP_REST

The project uses two techniques: **Rewrite engine** and **Representational State Transfer**. 
Although it is not mandatory, these are often used collectively.


## Rewrite engine
A rewrite engine is part of the webserver configuration,
realised either in a *.htaccess* - file (Apache) or in the server configuration (nginx, Lighttpd...) itself. 
It determines the mapping between incoming requests (URLs) and application logic.
The most unpretentious usage is providing fallbacks for unfound files;
since the URL information are kept, much more imaginative solutions are possible.

Assuming URLs shall be interpreted following the schema  
> BASE_URL/CLASS_NAME/FUNCTION_NAME/ARGUMENT1/ARGUMENT2/ARGUMENT3/...

and the rewrite engine has the rule  
> (.+) index.php/$1         # map everything to 'index.php'

a typical aproach would be:

```
// index.php
class Paint {
    public function brigthness ($first_colour, $second_colour) {
        echo "Possible are " . $first_colour . " and " . $second_colour;
    }
    public function colours ($first_colour, $second_colour, $third_colour) {
        echo "Colours are " . $first_colour . ", " . $second_colour . " and " . $third_colour;
    }
}

$page_components = explode("/", trim($_SERVER['PATH_INFO'], "/"));
$parameters = Array();
for ( $i = 2; $i < count($page_components); $i++ )
    $parameters[] = $page_components[$i];
call_user_func_array(Array(ucfirst($page_components[0]), $page_components[1]), $parameters);
```

Calling  
    *BASE_URL/paint/brigthness/black/white*  
would lead to output  
    **Possible are black and white**  
while calling  
    *BASE_URL/paint/colours/red/green/blue*  
would lead to output  
    **Colours are red, green and blue**

*To simplify things, this example does not use error handling nor security mechanisms at all*


## Representational State Transfer (REST)
When a network address is called, it can be done using HTTP methods
**GET**, **POST**, **PUT** or **DELETE** 
(theoretically also *PATCH*, *HEAD*, *OPTIONS*, *CONNECT* and *TRACE*,
but this is out of this scope).

Typical usage is
- *GET*     Retrieves the ressource without changing anything.
Limited additional information can be sent with the request.
The default procedure when calling a web address in a browser 
- *POST*    Sends information to the called address.
Creates a new ressource (physically or in a database).
Can be invoked from a browser using forms 
- *PUT*     Alters an existing ressource (eventually creates it if not existent).
Cannot be invoked from a browser
- *DELETE*  Deletes an existing ressource.
Cannot be invoked from a browser 

Although this usage is meaningful, HTTP methods could be interpreted differently, e.g. 
    - *GET*     Write characters 'g', 'e' and 't' into the log file 
    - *POST*    Send an email 
    - *PUT*     Update the server administrator's calendar 
    - *DELETE*  Shutdown the server 
Needless to mention that this is not a recommendation

When HTTP methods *PUT* or *DELETE* shall be used in a browser,
the most common trick is to create a form, add a hidden parameter 'method'
and send it using HTTP method *POST*.


## Branch **minimum**
A very short (but runnable) example with two classes
and two HTTP methods, showing
    - successful mapping
    - direct file access
    - error handling for undefined URL requests


## Object Orientation or not Object Orientation
Rewriting and REST can be used as well object oriented as functional.
**This shall not be a discussion about the advantages and disadvantages of OOP**.
For a project like the example provided in branch *master*,
one would probably prefer OOP (like here) for readability reasons
while for a pure technical API it could be worth thinking about a classless solution.


## Branch **master**
Simulation of a social network application. 
**It is not intended to use in production, not even as a starting point for production usage**
since it doesn't provide any security mechanisms at all.
To demonstrate this, not even passwords are used.

Prerequsite is a database connection; credentials must be given in 
    *includes/configuration/config.ini* 
Database and table description can be found in 
    *assets/create.sql*

To be usable with a server different from Apache,
*.htaccess* - files have to be translated.

Technically, the requested URL reflects a multi-dimensional matrix:
    - First url part (controller class)
    - Second url part (user id; only class 'User')
    - HTTP method (GET, POST, PUT, DELETE)
    - $_SESSION (logged in as distinct user or not)

GET         Home        ...         ...             (Displays home page)
POST        Home        ...         logged out      (Logs in as a distinct user)
DELETE      Home        ...         logged in       (Logs out)

GET         Account     ...         logged out      (Shows form to create an account)
GET         Account     ...         logged in       (Shows form to edit existing account)
POST        Account     ...         logged out      (Receives data to create an account)
PUT         Account     ...         logged in       (Receives data to edit existing account)

GET         User        user id     ...             (Displays user specific public data)
POST        User        user id     logged in       (Receives data of new message)
DELETE      User        user id     logged in       (Deletes a message; only possible when user id and $_SESSION['user_id'] are identical)
