<?php
//header("Content-type: text/html; charset=utf-8");

//$signMsg = 'MzySVB%2FNFqyAt0zx1P83IKd3O%2BXPriJZGbvGokP7n9eOgyxTbNS5Vc30c1FpPlWJyghnc6FDDLVOUAPLfwurgRi2IUKW0HbJRiQGpVdbMdGXVSJ5bOXGmh8Ji5AUxsPUK%2ByWP51%2FYIGm7I6NN5oGiJetMiwq1hcV4%2BgiNWLF4mo%3D';
$signMsg = "yGS0%2Bppxh0lIMSybJbRBpDG%2BH5blKDI5v7rOQ0Rnl4i7FsJbsBPexiLnJdEHEmnC9XGaKnVJHDQzK11AqmmPyzXwcCYz8YgDHFH3bbeY5tCdEpCALfXGRySXdlGUa6FN7Tj6mHZLsmW1qSGm1mFhuh2%2FDfBlXD2jCVjpAdWxGjQ%3D";
$signMsgDe=	urldecode($signMsg);
//echo $signMsgDe;
$MAC=base64_decode($signMsgDe);
echo $MAC;






