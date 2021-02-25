<?php

class HtmlTemplate
{
    public static function layout($content)
    {
        return '<!DOCTYPE html>
              <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <title>' . APP_NAME . '</title>
                </head>
                <body>
                  <div style=\'background: #FAFAFA; padding: 5rem 0; text-align: center;\'>
                    <div style=\'max-width:590px!important; width:590px; background: white;padding: 1rem;margin: auto;\'>
                       ' . $content . '
                    </div>
                    <div style="text-align: center; color: #888888; margin-top: 1rem;">
                       Copyright ©' . date('Y') . ' ' . APP_AUTHOR . '
                    </div>
                  </div>
                </body>
              </html>
        ';
    }
}
