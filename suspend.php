<?php

/*
 * Required Environment Variables:
 *
 * SUSPEND_PASSPHRASE
 * SUSPEND_MAIL_ADDRESS
 *
 * We suggest to use a UUID or something strong like this for the passphrase.
 *
 */

$success = true;
if (false === $type = getenv('SUSPEND_TYPE')) {
    $type = "default";
}

/**
 * Check if all required configuration values are present
 *
 * @return bool
 */
function checkRequirements()
{
    return empty(getenv('SUSPEND_PASSPHRASE')) || empty(getenv('SUSPEND_MAIL_ADDRESS'));
}

/**
 * Backup existing .htaccess and write new one to web directory
 *
 * @param string $type
 *
 * @return void
 */
function updateHtaccess(string $type)
{
    if ("suexec_subdir" === $type) {
        $basePath = dirname($_SERVER['SCRIPT_FILENAME']);
    } else {
        $basePath = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : realpath(dirname(__FILE__));
    }
    $htaccess = $basePath.'/.htaccess';

    if (file_exists($htaccess)) {
        copy($htaccess, sprintf('%s/.htaccess.bak.%s', $basePath, time()));
    }

    $data = "Require all denied\n";

    file_put_contents($htaccess, $data);
}

/**
 * Send information mail to administration contact
 *
 * @param string $type
 *
 * @return void
 */
function sendInfo(string $type)
{
    if ("suexec_subdir" === $type) {
        $siteName = sprintf('%s%s', $_SERVER['SERVER_NAME'], dirname($_SERVER['REQUEST_URI']));
    } else {
        $siteName = $_SERVER['SERVER_NAME'];
    }
    $subject = sprintf('Website "%s" was suspended', $siteName);
    $message = sprintf('The website under "%s" was suspended', $siteName);
    mail(getenv('SUSPEND_MAIL_ADDRESS'), $subject, $message);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['passphrase']) && $_POST['passphrase'] === getenv('SUSPEND_PASSPHRASE')) {
        updateHtaccess($type);
        sendInfo($type);
    } else {
        $success = false;
    }
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>WEBSITE SUSPENSION</title>
    <style type="text/css">
        /*!
         * Milligram v1.3.0
         * https://milligram.github.io
         *
         * Copyright (c) 2017 CJ Patoilo
         * Licensed under the MIT license
         */
        *, *:after, *:before {
            box-sizing: inherit
        }

        html {
            box-sizing: border-box;
            font-size: 62.5%
        }

        body {
            color: #606c76;
            font-family: 'Roboto', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 1.6em;
            font-weight: 300;
            letter-spacing: .01em;
            line-height: 1.6
        }

        .button, button, input[type='button'], input[type='reset'], input[type='submit'] {
            background-color: #428bca;
            border: 0.1rem solid #428bca;
            border-radius: .4rem;
            color: #fff;
            cursor: pointer;
            display: inline-block;
            font-size: 1.1rem;
            font-weight: 700;
            height: 3.8rem;
            letter-spacing: .1rem;
            line-height: 3.8rem;
            padding: 0 3.0rem;
            text-align: center;
            text-decoration: none;
            text-transform: uppercase;
            white-space: nowrap
        }

        .button:focus, .button:hover, button:focus, button:hover, input[type='button']:focus, input[type='button']:hover, input[type='reset']:focus, input[type='reset']:hover, input[type='submit']:focus, input[type='submit']:hover {
            background-color: #606c76;
            border-color: #606c76;
            color: #fff;
            outline: 0
        }

        .button[disabled], button[disabled], input[type='button'][disabled], input[type='reset'][disabled], input[type='submit'][disabled] {
            cursor: default;
            opacity: .5
        }

        .button[disabled]:focus, .button[disabled]:hover, button[disabled]:focus, button[disabled]:hover, input[type='button'][disabled]:focus, input[type='button'][disabled]:hover, input[type='reset'][disabled]:focus, input[type='reset'][disabled]:hover, input[type='submit'][disabled]:focus, input[type='submit'][disabled]:hover {
            background-color: #428bca;
            border-color: #428bca
        }

        .button.button-outline, button.button-outline, input[type='button'].button-outline, input[type='reset'].button-outline, input[type='submit'].button-outline {
            background-color: transparent;
            color: #428bca
        }

        .button.button-outline:focus, .button.button-outline:hover, button.button-outline:focus, button.button-outline:hover, input[type='button'].button-outline:focus, input[type='button'].button-outline:hover, input[type='reset'].button-outline:focus, input[type='reset'].button-outline:hover, input[type='submit'].button-outline:focus, input[type='submit'].button-outline:hover {
            background-color: transparent;
            border-color: #606c76;
            color: #606c76
        }

        .button.button-outline[disabled]:focus, .button.button-outline[disabled]:hover, button.button-outline[disabled]:focus, button.button-outline[disabled]:hover, input[type='button'].button-outline[disabled]:focus, input[type='button'].button-outline[disabled]:hover, input[type='reset'].button-outline[disabled]:focus, input[type='reset'].button-outline[disabled]:hover, input[type='submit'].button-outline[disabled]:focus, input[type='submit'].button-outline[disabled]:hover {
            border-color: inherit;
            color: #428bca
        }

        .button.button-clear, button.button-clear, input[type='button'].button-clear, input[type='reset'].button-clear, input[type='submit'].button-clear {
            background-color: transparent;
            border-color: transparent;
            color: #428bca
        }

        .button.button-clear:focus, .button.button-clear:hover, button.button-clear:focus, button.button-clear:hover, input[type='button'].button-clear:focus, input[type='button'].button-clear:hover, input[type='reset'].button-clear:focus, input[type='reset'].button-clear:hover, input[type='submit'].button-clear:focus, input[type='submit'].button-clear:hover {
            background-color: transparent;
            border-color: transparent;
            color: #606c76
        }

        .button.button-clear[disabled]:focus, .button.button-clear[disabled]:hover, button.button-clear[disabled]:focus, button.button-clear[disabled]:hover, input[type='button'].button-clear[disabled]:focus, input[type='button'].button-clear[disabled]:hover, input[type='reset'].button-clear[disabled]:focus, input[type='reset'].button-clear[disabled]:hover, input[type='submit'].button-clear[disabled]:focus, input[type='submit'].button-clear[disabled]:hover {
            color: #428bca
        }

        code {
            background: #f4f5f6;
            border-radius: .4rem;
            font-size: 86%;
            margin: 0 .2rem;
            padding: .2rem .5rem;
            white-space: nowrap
        }

        pre {
            background: #f4f5f6;
            border-left: 0.3rem solid #428bca;
            overflow-y: hidden
        }

        pre > code {
            border-radius: 0;
            display: block;
            padding: 1rem 1.5rem;
            white-space: pre
        }

        input[type='email'], input[type='number'], input[type='password'], input[type='search'], input[type='tel'], input[type='text'], input[type='url'], textarea, select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-color: transparent;
            border: 0.1rem solid #d1d1d1;
            border-radius: .4rem;
            box-shadow: none;
            box-sizing: inherit;
            height: 3.8rem;
            padding: .6rem 1.0rem;
            width: 100%
        }

        input[type='email']:focus, input[type='number']:focus, input[type='password']:focus, input[type='search']:focus, input[type='tel']:focus, input[type='text']:focus, input[type='url']:focus, textarea:focus, select:focus {
            border-color: #428bca;
            outline: 0
        }

        label, legend {
            display: block;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: .5rem
        }

        fieldset {
            border-width: 0;
            padding: 0
        }

        input[type='checkbox'], input[type='radio'] {
            display: inline
        }

        .container {
            margin: 0 auto;
            max-width: 112.0rem;
            padding: 0 2.0rem;
            position: relative;
            width: 100%
        }

        .row {
            display: flex;
            flex-direction: column;
            padding: 0;
            width: 100%
        }

        @media (min-width: 40rem) {
            .row {
                flex-direction: row;
                margin-left: -1.0rem;
                width: calc(100% + 2.0rem)
            }

            .row .column {
                margin-bottom: inherit;
                padding: 0 1.0rem
            }
        }

        a {
            color: #428bca;
            text-decoration: none
        }

        a:focus, a:hover {
            color: #606c76
        }

        .button, button, dd, dt, li {
            margin-bottom: 1.0rem
        }

        fieldset, input, select, textarea {
            margin-bottom: 1.5rem
        }

        blockquote, dl, figure, form, ol, p, pre, table, ul {
            margin-bottom: 2.5rem
        }

        b, strong {
            font-weight: bold
        }

        p {
            margin-top: 0
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 300;
            letter-spacing: -.1rem;
            margin-bottom: 2.0rem;
            margin-top: 0
        }

        h1 {
            font-size: 4.6rem;
            line-height: 1.2
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="column-10">
            <h1 style="text-transform: uppercase;">Website suspension form</h1>
            <?php if (checkRequirements()) : ?>
                <strong>The configuration is not valid! Please contact your hosting administrator!</strong>
            <?php else : ?>
                <p><strong>By entering your passphrase, you can stop all accesses to this site. <br />
                    Caution: You'll need to contact us via mail to reactivate the website!</strong>
                </p>
                <?php if (false === $success) : ?>
                    <p style="color: #428bca">The passphrase is wrong. Please try again or go away!</p>
                <?php endif; ?>
                <form method="post">
                    <fieldset>
                        <label for="passphrase">Passphrase</label>
                        <input type="password" name="passphrase" id="passphrase" required>
                        <input class="button button-outline" type="submit" value="Yes, I want to suspend this website!">
                    </fieldset>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
