<?php
echo '@prefix schema: <http://schema.org/> .';
fgets(STDIN); //skipp header
while ($rawdata = fgetcsv(STDIN, 2048, ';')) {
	printf('resource:comune_%s a schema:City;', $rawdata[4]);
	printf('schema:name """%s""".', utf8_encode ($rawdata[5]));
}