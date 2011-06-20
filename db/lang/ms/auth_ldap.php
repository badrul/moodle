<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_ldap', language 'ms', branch 'MOODLE_20_STABLE'
 *
 * @package   auth_ldap
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_ldap_bind_dn'] = 'Jika Anda ingin menggunakan "bind-user" untuk mencari pengguna, tentukanlah disini. Misalnya \'cn=ldapuser,ou=public,o=org\'';
$string['auth_ldap_bind_pw'] = 'Kata laulan untuk';
$string['auth_ldap_contexts'] = 'Senarai konteks dimana pengguna terletak. Asingkan konteks-konteks yang berbeza dengan \';\'. Sebagai contoh: \'ou=users,o=org; ou=others,o=org\'';
$string['auth_ldap_create_context'] = 'Jika anda membenarkan pendaftaran pengguna menggunakan email, perincikan konteks dimana pengguna didaftarkan. Ini mestilah berbeza daripada pengguna yang lain untuk mengelakkan isu-isu keselamatan. Anda tidak perlu menambahkan konteks ini pada ldap_context-variable, kerana Moodle akan mencari pengguna-pengguna konteks ini secara automatik.';
$string['auth_ldap_creators'] = 'Senarai kumpulan yang mana anggota-anggotanya dibenarkan mencipta kursus-kursus baru. Asingkan kumpulan berbeza degan ";". Selalunya seperti \'cn=teachers,ou=staff,o=myorg\'';
$string['auth_ldapdescription'] = 'Cara ini melakukan pengesahan melalui pelayan LDAP luaran.
Jika nama pengguna dan password kata laluan adalah sah, Moodle akan mencipta pengguna baru 
dalam pangkalan datanyaa. Modul ini dapat membaca atribut pengguna dari LDAP dan memasukkan terlebih dahulu dalam ruangan yang diperlukan oleh Moodle.  Untuk daftar masuk selanjutnya hanya nama pengguna dan kata laluan yang diperiksa.';
$string['auth_ldapextrafields'] = 'Ruang-ruang ini adalah pilihan. Anda dapat memilih untuk memasukkan terlebih dahulu beberapa ruang pengguna dari Moodle dengan maklumat dari <b>ruangan LDAP</b> yang anda tentukan disini. <br />Jika anda kosongkan, maka tiada maklumat yang akan digunakan.<br />Walau bagaimana pun, pengguna akan dapat mengubah kesemua ruangan ini setelah mereka daftar masuk.';
$string['auth_ldap_host_url'] = 'Tentukan hos LDAP dengan format URL  seperti \'ldap://ldap.myorg.com/\' or \'ldaps://ldap.myorg.com/\'';
$string['auth_ldap_memberattribute'] = 'Perincikan atribut pengguna, apabila pengguna adalah anggota sesebuah kumpulan. Selalunya \'member\'';
$string['auth_ldap_search_sub'] = 'Masukkan nilai <> 0 jika anda ingin untuk mencari pengguna dari sub-konteks.';
$string['auth_ldap_update_userinfo'] = 'Kemaskini maklumat pengguna (nama depan, namaakhir, alamat..) dari LDAP ke Moodle. Lihat /auth/ldap/attr_mappings.php untuk maklumat pemetaannya';
$string['auth_ldap_user_attribute'] = 'Atribut yang digunakan untuk nama/cari pengguna. Biasanya \'cn\'.';
$string['auth_ldap_version'] = 'Versi protokol LDAP pada server ini.';
$string['pluginname'] = 'Gunakan pelayan LDAP';
$string['auth_ldaptitle'] = 'Gunakan pelayan LDAP';
