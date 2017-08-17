<?php declare(strict_types=1);

namespace Pahout\Formatter;

/**
* JSON output formatter
*
* Following example (pretty-print):
*
* ```
*{
*  "files": [
*    "./subdir/test.php",
*    "./syntax_error.php",
*    "./test.php"
*  ],
*  "hints": [
*    {
*      "type": "ShortArraySyntax",
*      "message": "Use [...] syntax instead of array(...) syntax.",
*      "filename": "./subdir/test.php",
*      "lineno": 4
*    },
*    {
*      "type": "SyntaxError",
*      "message": "syntax error, unexpected 'f' (T_STRING)",
*      "filename": "./syntax_error.php",
*      "lineno": 3
*    },
*    {
*      "type": "ShortArraySyntax",
*      "message": "Use [...] syntax instead of array(...) syntax.",
*      "filename": "./test.php",
*      "lineno": 3
*    }
*  ]
*}
* ```
*/
class JSON extends Base
{
    /**
    * Print hints to the console throught output interface of symfony console.
    *
    * @return void
    */
    public function print()
    {
        $this->output->write(json_encode([
            'files' => $this->files,
            'hints' => $this->hints,
        ]));
    }
}
