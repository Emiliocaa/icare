<?php
//No se puede integrar directamente en general/index.php si quieres que la URL raíz (/proyectos/icare/) devuelva 404: esa página solo se ejecuta al entrar en /proyectos/icare/general/.

http_response_code(404);
