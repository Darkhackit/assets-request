<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'it@primeinsuranceghana',
            'password' => bcrypt('Prime@1234')
        ]);
        Employee::create([
            'name' => 'JERRY ',
            'lastname' => "DANSO",
            'email' => 'jdanso@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-04",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'KENNEDY KUSI ',
            'lastname' => "KYENKYEHENE",
            'email' => 'kkyenkyehene@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-04",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ISAAC',
            'lastname' => "LAMPTEY",
            'email' => 'ilamptey.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-06",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ALSWELL',
            'lastname' => "ASIEDU",
            'email' => 'aasiedu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-10",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'EDMUND',
            'lastname' => "SRENYAME",
            'email' => 'esrenyame@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-12",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ALFRED DONALD',
            'lastname' => "WILLIAMS",
            'email' => 'awilliams@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-14",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'PHILIP KWAME',
            'lastname' => "ODURO",
            'email' => 'phoduro@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-15",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ABIGAIL KESEWAA',
            'lastname' => "BOATENG",
            'email' => 'akboateng@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-01-21",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'GOTTLIEB KOFI KRAH',
            'lastname' => "HASFORD",
            'email' => 'kkhashford@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-01-24",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ISHMAEL NII ABBEYASHIE',
            'lastname' => "OKAI",
            'email' => 'iabbeyashie.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-01",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'DANIEL OWUSU',
            'lastname' => "AMPONSAH",
            'email' => 'doamponsah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-03",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'FLORENCE KORKOI',
            'lastname' => "ARMAH",
            'email' => 'fkarmah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-02-08",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'FRANCIS KWEKU',
            'lastname' => "OHENE",
            'email' => 'fkohene@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-13",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'DANIEL',
            'lastname' => "MANSO",
            'email' => 'dmanso@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-15",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'KWADWO',
            'lastname' => "BOAHENE",
            'email' => 'kbsarpong@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-18",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'SENYO SEGBEDZI',
            'lastname' => "RICH",
            'email' => 'srsegbedzi@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-20",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'ELIKEM YAW',
            'lastname' => "AWAGAH",
            'email' => 'eawagah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-02-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'DOMINIC',
            'lastname' => "ANDREWS",
            'email' => 'adominic@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-01",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'JOYCE',
            'lastname' => "AWAH",
            'email' => 'jawah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-03-02",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'ERNEST SACKEY',
            'lastname' => "WOODE",
            'email' => 'ewsackey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-03",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'DANIEL TETTEH SIKA',
            'lastname' => "NARTEY",
            'email' => 'dsnartey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-05",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ERIC',
            'lastname' => "ACQUAH",
            'email' => 'eacquah.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-10",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'EUNICE MAAME YAA',
            'lastname' => "AMPONSAH",
            'email' => 'eyamponsah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-03-18",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'FRANKLIN',
            'lastname' => "HUNZEY",
            'email' => 'fhunzey.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-18",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'PENNANT ATTAH JR',
            'lastname' => "KYEREMEH",
            'email' => 'jkyeremeh@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ALFRED JUSTICE',
            'lastname' => "ABABIO",
            'email' => 'ajababio.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-03-27",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'PHILIP ROGERS',
            'lastname' => "AKPATAH",
            'email' => 'prakpatah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-04-02",
            'user_id' => '1'
        ]);
        // Employee::create([
        //     'firstname' => 'NANA OSEI',
        //     'lastname' => "DARFOUR",
        //     'email' => 'nodarfour@primeinsuranceghana.com',
        //     'branch_id' => '1',
        //     'gender' => 'male',
        //     'dob' => "2023-04-03",
        //     'user_id' => '1'
        // ]);
        Employee::create( [
            'firstname' => 'NANA OSEI',
            'lastname' => "DARFOUR",
            'email' => 'nodarfour@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-04-03",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'FLORENCE OWUSU',
            'lastname' => "SEKYERE",
            'email' => 'fosekyere@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-04-13",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'SANDRA',
            'lastname' => "AGYEKUM",
            'email' => 'sagyekum@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-04-27",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ESTHER',
            'lastname' => "ASIEDU",
            'email' => 'ebasiedu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-04-29",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'GEORGE',
            'lastname' => "QUARTEY",
            'email' => 'gquartey.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-04-30",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'VANESSA DZIFA',
            'lastname' => "GBENA",
            'email' => 'vdgbena@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-05-02",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'MAHMOUD',
            'lastname' => "ISSAH",
            'email' => 'missah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-05-08",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'DANIEL OSEI',
            'lastname' => "AGYEMANG",
            'email' => 'doagyemang@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-05-09",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'PERPETUA MELISSA',
            'lastname' => "BAIDOO",
            'email' => 'pbaidoo@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-05-18",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'MARY ANN TWUM',
            'lastname' => "AMPOFO",
            'email' => 'mtampofo@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-05-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ESTHER',
            'lastname' => "ANKOMAH",
            'email' => 'eankomah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-05-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'EMMANUEL',
            'lastname' => "GHUNNEY",
            'email' => 'eghunney.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-05-26",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'SAMUEL',
            'lastname' => "MADDY",
            'email' => 'smaddy@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-05-31",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ISAAC ADJEI',
            'lastname' => "KUMI",
            'email' => 'iadjeikumi@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-05",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'JOSEPH NYAKOTEI',
            'lastname' => "DORH",
            'email' => 'jdorh@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-06",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'DAVID',
            'lastname' => "FRIKO",
            'email' => 'dfriko@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-07",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'RICHARD',
            'lastname' => "ACHEAMPONG",
            'email' => 'racheampong@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-11",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'MICHEAL',
            'lastname' => "NORTEY",
            'email' => 'mnortey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-18",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'AARON MENSAH',
            'lastname' => "QUAYE",
            'email' => 'amquaye@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-06-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'JACQUELINE NAA SHOME',
            'lastname' => "NORTEY",
            'email' => 'jsnortey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-06-29",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'CYNTHIA',
            'lastname' => "CUDJOE",
            'email' => 'ccudjoe@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-07-01",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'EWURAMA POMAAH',
            'lastname' => "DANSO",
            'email' => 'epdanso@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-07-09",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'BENJAMIN KWAMENA',
            'lastname' => "ARTHUR",
            'email' => 'bkarthur@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-07-13",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'EMMANUEL',
            'lastname' => "ARTHUR",
            'email' => 'earthur@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-07-15",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'EDMUND',
            'lastname' => "ARCHER",
            'email' => 'earcher@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-07-16",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'CHARLES KWAME',
            'lastname' => "ADABRAH",
            'email' => 'ckadabrah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-07-22",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'DILYS',
            'lastname' => "BODOM",
            'email' => 'dkoomson@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-07-27",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'JOEL LARTEY',
            'lastname' => "ASIRIFI",
            'email' => 'jalartey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-07-31",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ISAAC',
            'lastname' => "DONKOR",
            'email' => 'idonkor.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-08-01",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'MICHEAL',
            'lastname' => "ANTWI",
            'email' => 'mantwi.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-08-16",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'ISRAEL',
            'lastname' => "AGLOGU",
            'email' => 'iaglogu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-08-22",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'LAWRENCE OSEI',
            'lastname' => "POKU",
            'email' => 'lopoku@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-08-30",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'JOSIAH',
            'lastname' => "YAMUSAH",
            'email' => 'jyamusah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-07",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'SAMUEL',
            'lastname' => "MENSAH",
            'email' => 'smensah.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-07",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'TIMOTHY OWUSU',
            'lastname' => "NORTEY",
            'email' => 'tnortey@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-08",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'STEPHEN ADU',
            'lastname' => "DARKO",
            'email' => 'sadudarko.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-09",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'FRANK',
            'lastname' => "BADU",
            'email' => 'fbadu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-17",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'SARAH AFIA AFRA',
            'lastname' => "MENSAH",
            'email' => 'smensah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-09-19",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ASIEDU KWAKU',
            'lastname' => "SARFO",
            'email' => 'ksasiedu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-25",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'NICHOLAS NUNNO',
            'lastname' => "NTREH",
            'email' => 'nnntreh@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-27",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'OSAHENE APRAKU',
            'lastname' => "AMANKWATIA",
            'email' => 'oaamankwatia@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-28",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'OHENE FREDERICK',
            'lastname' => "BUDU",
            'email' => 'fobudu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-09-28",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'MICHAEL',
            'lastname' => "CROMWELL",
            'email' => 'mcromwell@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-10-05",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'BENEDICTA',
            'lastname' => "TORGBOR",
            'email' => 'btorgbor@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-10-11",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'YAW',
            'lastname' => "GYAN",
            'email' => 'ygyan@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-10-13",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'ELSIE BAFFOUR',
            'lastname' => "KWAKYE",
            'email' => 'eladu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-10-13",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'SANDRA',
            'lastname' => "AIKINS",
            'email' => 'sbaikins@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-10-13",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'NATASHA',
            'lastname' => "ANSAH",
            'email' => 'nansah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-10-17",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'JEFFERSON BAKKER',
            'lastname' => "ASIAMAH",
            'email' => 'jbasiamah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-10-17",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'MOHAMMED',
            'lastname' => "ADAMS",
            'email' => 'mmadams@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-10-23",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'EBENEZER',
            'lastname' => "ACHEAMPONG",
            'email' => 'eamponsah.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-10-24",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'EFUA AMPOFOWAA',
            'lastname' => "YEBOAH",
            'email' => 'ayeboah@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-11-03",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'SAMUEL',
            'lastname' => "ACQUAH",
            'email' => 'sacquah.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-11-04",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'KWAME',
            'lastname' => "ATAKORA",
            'email' => 'katakora.prime@gmail.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-11-12",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'NANCY NOTBURGA',
            'lastname' => "BOATENG",
            'email' => 'nboateng@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-12-03",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'VANESSA BOYE',
            'lastname' => "HAMMOND",
            'email' => 'vbhammond@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-12-10",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'BELINDA OWUSU',
            'lastname' => "AGYAPONG",
            'email' => 'baowusu@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-12-12",
            'user_id' => '1'
        ]);
        Employee::create([
            'firstname' => 'AKOSUA GYAMFUAA',
            'lastname' => "APAU",
            'email' => 'agappau@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-12-19",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'SHADRACK SASU',
            'lastname' => "BOADI",
            'email' => 'ssboadi@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-12-20",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'OSCAR AWINDENABA',
            'lastname' => "AKANYEH",
            'email' => 'oaakanyeh@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-12-21",
            'user_id' => '1'
        ]);
        Employee::create(   [
            'firstname' => 'GEORGE APPIAH',
            'lastname' => "ADDAE",
            'email' => 'gaddae@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-12-23",
            'user_id' => '1'
        ]);
        Employee::create(  [
            'firstname' => 'HARDI',
            'lastname' => "SALIFU",
            'email' => 'shardi@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'male',
            'dob' => "2023-12-26",
            'user_id' => '1'
        ]);
        Employee::create( [
            'firstname' => 'PRISCILLA YAA',
            'lastname' => "AMAKYE",
            'email' => 'pamakye@primeinsuranceghana.com',
            'branch_id' => '1',
            'gender' => 'female',
            'dob' => "2023-12-30",
            'user_id' => '1'
        ]);
    }
}
