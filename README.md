# Jarzon/Capture

Take a screenshot/pdf/html, of a page using Chrome.

```PHP
    $capture = new Capture();
    
    $stout = $capture->screenshot('http://google.com', "destination/screenshot.png");
    $stout = $capture->pdf('http://google.com', "destination/screenshot.pdf");
    $html = $capture->html('http://google.com');
```

Configure Chrome location
```PHP
    $capture = new Capture([
        'chrome_path' => ($capture->isWindows())? '"C:/Program Files (x86)/Google/Chrome/Application/chrome.exe"': '/usr/bin/google-chrome'
    ]);
```