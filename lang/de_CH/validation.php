<?php

/*
 * Swiss German validation messages (no "ß"). Covers the rules the platform
 * uses today (sign in, sign up, password reset, landing questions); anything
 * missing falls back to English.
 */
return [
    'confirmed' => ':Attribute stimmt nicht mit der Bestätigung überein.',
    'current_password' => 'Das Passwort ist nicht korrekt.',
    'email' => ':Attribute muss eine gültige E-Mail-Adresse sein.',
    'lowercase' => ':Attribute darf nur Kleinbuchstaben enthalten.',
    'max' => [
        'string' => ':Attribute darf höchstens :max Zeichen haben.',
    ],
    'min' => [
        'string' => ':Attribute muss mindestens :min Zeichen haben.',
    ],
    'password' => [
        'letters' => ':Attribute muss mindestens einen Buchstaben enthalten.',
        'mixed' => ':Attribute muss Gross- und Kleinbuchstaben enthalten.',
        'numbers' => ':Attribute muss mindestens eine Zahl enthalten.',
        'symbols' => ':Attribute muss mindestens ein Sonderzeichen enthalten.',
        'uncompromised' => 'Dieses Passwort ist in einem Datenleck aufgetaucht. Bitte wählt ein anderes.',
    ],
    'prohibited' => ':Attribute ist nicht erlaubt.',
    'required' => ':Attribute wird benötigt.',
    'string' => ':Attribute muss ein Text sein.',
    'unique' => ':Attribute wird bereits verwendet.',

    'attributes' => [
        'name' => 'Name',
        'email' => 'E-Mail-Adresse',
        'password' => 'Passwort',
        'password_confirmation' => 'Passwortbestätigung',
        'question' => 'Frage',
    ],
];
