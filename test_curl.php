<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/FYP%20PROJECT%20DEVELOPMENT/public/api/slots?practitioner_id=1&date=2026-07-27");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// send dummy session cookie if needed, but wait, without session it will redirect to login.
// let's just see if it redirects to login or 404s.
curl_setopt($ch, CURLOPT_HEADER, true);
$response = curl_exec($ch);
curl_close($ch);
echo $response;
