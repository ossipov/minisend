<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ $subject }}</title>
    <style>body {overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;font-family: 'Open Sans', sans-serif;}  body * {caret-color: currentColor;}  body h1, body h2, body h3, body p, body pre, body blockquote {margin-bottom: 0.85rem;}  body h1 {font-size: 1.75rem;}  body h2 {font-size: 1.35rem;font-weight: bold;}  body h3 {font-size: 1.15rem;text-transform: uppercase;font-weight: bold;}  body pre {padding: 0.7rem 1rem;border-radius: 5px;background: #000000;color: #ffffff;font-size: 0.8rem;overflow-x: auto;}  body pre code {display: block;}  body p code {padding: 0.2rem 0.4rem;border-radius: 5px;font-size: 0.8rem;font-weight: bold;background: rgba(0, 0, 0, 0.1);color: rgba(0, 0, 0, 0.8);}  body ul {list-style: disc;}  body ol {list-style: decimal;}  body ul, body ol {margin-bottom: 0.85rem;padding-left: 1rem;margin-left: 1rem;}  body li > p, body li > ol, body li > ul {margin: 0;}  body a {color: #2196F3;}  body blockquote {border-left: 3px solid rgba(0, 0, 0, 0.1);color: rgba(0, 0, 0, 0.8);padding-left: 0.8rem;font-style: italic;}  body blockquote p {margin: 0;}  body img {max-width: 100%;border-radius: 3px;}  body table {border-collapse: collapse;table-layout: fixed;width: 100%;margin: 0;overflow: hidden;}  body table td, body table th {min-width: 1em;border: 2px solid #dddddd;padding: 3px 5px;vertical-align: top;box-sizing: border-box;position: relative;}  body table td > *, body table th > * {margin-bottom: 0;}  body table th {font-weight: bold;text-align: left;}  body table .selectedCell:after {z-index: 2;position: absolute;content: "";left: 0;right: 0;top: 0;bottom: 0;background: rgba(200, 200, 255, 0.4);pointer-events: none;}  body table .column-resize-handle {position: absolute;right: -2px;top: 0;bottom: 0;width: 4px;z-index: 20;background-color: #adf;pointer-events: none;}  body .tableWrapper {margin: 1em 0;overflow-x: auto;}body hr {margin: 2rem 0; box-sizing: content-box; height: 0; overflow: visible; border-width: 1px 0 0 0; border-style: solid; border-color: #e2e8f0;} </style>
</head>
<body>
    {!! $html !!}
</body>
</html>