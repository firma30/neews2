<?php



require("config/conn.php");

require("functions.php");

session_start();





require "vendor/autoload.php";













use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;





if (isset($_GET['export_user_excel'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'uId');

    $sheet->setCellValue("C1", 'name');

    $sheet->setCellValue("D1", 'user_name');

    $sheet->setCellValue("E1", 'email');

    $sheet->setCellValue("F1", 'verified');

    $sheet->setCellValue("G1", 'language');

    $sheet->setCellValue("H1", 'sign_up_via');

    $sheet->setCellValue("I1", 'account_created');

    $sheet->setCellValue("J1", 'signed_in');

    $sheet->setCellValue("K1", 'last_active');



    $sql = "SELECT * FROM `users`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['uId']);

            $sheet->setCellValue("C".$cell."", $row['name']);

            $sheet->setCellValue("D".$cell."", $row['user_name']);

            $sheet->setCellValue("E".$cell."", $row['email']);

            $sheet->setCellValue("F".$cell."", $row['verified']);

            $sheet->setCellValue("G".$cell."", $row['language']);

            $sheet->setCellValue("H".$cell."", $row['sign_up_via']);

            $sheet->setCellValue("I".$cell."", $row['account_created']);

            $sheet->setCellValue("J".$cell."", $row['signed_in']);

            $sheet->setCellValue("K".$cell."", $row['last_active']);



            $cell++;

        }





    $filename = "users.xlsx";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");

    $writer->save("php://output");



}



if (isset($_GET['export_user_csv'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'uId');

    $sheet->setCellValue("C1", 'name');

    $sheet->setCellValue("D1", 'user_name');

    $sheet->setCellValue("E1", 'email');

    $sheet->setCellValue("F1", 'verified');

    $sheet->setCellValue("G1", 'language');

    $sheet->setCellValue("H1", 'sign_up_via');

    $sheet->setCellValue("I1", 'account_created');

    $sheet->setCellValue("J1", 'signed_in');

    $sheet->setCellValue("K1", 'last_active');



    $sql = "SELECT * FROM `users`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['uId']);

            $sheet->setCellValue("C".$cell."", $row['name']);

            $sheet->setCellValue("D".$cell."", $row['user_name']);

            $sheet->setCellValue("E".$cell."", $row['email']);

            $sheet->setCellValue("F".$cell."", $row['verified']);

            $sheet->setCellValue("G".$cell."", $row['language']);

            $sheet->setCellValue("H".$cell."", $row['sign_up_via']);

            $sheet->setCellValue("I".$cell."", $row['account_created']);

            $sheet->setCellValue("J".$cell."", $row['signed_in']);

            $sheet->setCellValue("K".$cell."", $row['last_active']);



            $cell++;

        }





    $filename = "users.csv";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Csv");

    $writer->save("php://output");



}



if (isset($_GET['export_user_pdf'])) {



  $sql = "SELECT * FROM `users`";

  $query_run = mysqli_query($conn, $sql);



  if (mysqli_num_rows($query_run) > 0) {

        $html = '

        <style>

        .heading, .heading > td{

            background: #15122d;

            color: #fff;

            padding: 10px 20px;

          }

          

           td{

            border-bottom: 1px solid #15122d;

             border-right: 1px solid #15122d;

          }

        </style>

        <table>';

            $html.='<tr class="heading"><td style="color: #fff;">ID</td><td  style="color: #fff;">uID</td><td  style="color: #fff;">Email</td><td  style="color: #fff;">User Name</td><td  style="color: #fff;">language</td><td  style="color: #fff;">Sign Up Via</td><td  style="color: #fff;">Verified</td><td  style="color: #fff;">Signed In</td><td  style="color: #fff;">Last Active</td></tr>';

            while ($row = mysqli_fetch_assoc($query_run)) {

                $html.='<tr><td>'.$row['id'].'</td><td>'.$row['uId'].'</td><td>'.$row['email'].'</td><td>'.$row['user_name'].'</td><td>'.$row['language'].'</td><td>'.$row['sign_up_via'].'</td><td>'.$row['verified'].'</td><td>'.$row['signed_in'].'</td><td>'.$row['last_active'].'</td></tr>';

            }

        $html.= '</table>';

  } else{

    $html = 'Data not found!';

  }

        // echo $html;



    $mpdf = new \Mpdf\Mpdf();

    $mpdf->writeHTML($html);

    $mpdf->output('users.pdf', 'D');





}











if (isset($_GET['export_category_csv'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_name');

    $sheet->setCellValue("C1", 'icon');

    $sheet->setCellValue("D1", 'image');

    $sheet->setCellValue("E1", 'total_articles');

    $sheet->setCellValue("F1", 'language');

    $sheet->setCellValue("G1", 'created_at');





    $sql = "SELECT * FROM `categories`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_name']);

            $sheet->setCellValue("C".$cell."", $row['icon']);

            $sheet->setCellValue("D".$cell."", $row['image']);

            $sheet->setCellValue("E".$cell."", $row['total_articles']);

            $sheet->setCellValue("F".$cell."", $row['language']);

            $sheet->setCellValue("G".$cell."", $row['created_at']);



            $cell++;

        }





    $filename = "category.csv";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Csv");

    $writer->save("php://output");



}



if (isset($_GET['export_category_excel'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_name');

    $sheet->setCellValue("C1", 'icon');

    $sheet->setCellValue("D1", 'image');

    $sheet->setCellValue("E1", 'total_articles');

    $sheet->setCellValue("F1", 'language');

    $sheet->setCellValue("G1", 'created_at');





    $sql = "SELECT * FROM `categories`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_name']);

            $sheet->setCellValue("C".$cell."", $row['icon']);

            $sheet->setCellValue("D".$cell."", $row['image']);

            $sheet->setCellValue("E".$cell."", $row['total_articles']);

            $sheet->setCellValue("F".$cell."", $row['language']);

            $sheet->setCellValue("G".$cell."", $row['created_at']);



            $cell++;

        }





    $filename = "category.xlsx";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");

    $writer->save("php://output");



}



if (isset($_GET['export_articles_csv'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_id');

    $sheet->setCellValue("C1", 'category_name');

    $sheet->setCellValue("D1", 'title');

    $sheet->setCellValue("E1", 'cover_image');

    $sheet->setCellValue("F1", 'description');

    $sheet->setCellValue("G1", 'views');

    $sheet->setCellValue("H1", 'likes');

    $sheet->setCellValue("I1", 'saves');

    $sheet->setCellValue("J1", 'is_trending');

    $sheet->setCellValue("K1", 'is_breaking');

    $sheet->setCellValue("L1", 'language');

    $sheet->setCellValue("M1", 'reports');

    $sheet->setCellValue("N1", 'date_created');





    $sql = "SELECT * FROM `articles`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_id']);

            $sheet->setCellValue("C".$cell."", $row['category_name']);

            $sheet->setCellValue("D".$cell."", $row['title']);

            $sheet->setCellValue("E".$cell."", $row['cover_image']);

            $sheet->setCellValue("F".$cell."", $row['description']);

            $sheet->setCellValue("G".$cell."", $row['views']);

            $sheet->setCellValue("H".$cell."", $row['likes']);

            $sheet->setCellValue("I".$cell."", $row['saves']);

            $sheet->setCellValue("J".$cell."", $row['is_trending']);

            $sheet->setCellValue("K".$cell."", $row['is_breaking']);

            $sheet->setCellValue("L".$cell."", $row['language']);

            $sheet->setCellValue("M".$cell."", $row['reports']);

            $sheet->setCellValue("N".$cell."", $row['date_created']);



            $cell++;

        }





    $filename = "articles.csv";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Csv");

    $writer->save("php://output");



}



if (isset($_GET['export_articles_excel'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_id');

    $sheet->setCellValue("C1", 'category_name');

    $sheet->setCellValue("D1", 'title');

    $sheet->setCellValue("E1", 'cover_image');

    $sheet->setCellValue("F1", 'description');

    $sheet->setCellValue("G1", 'views');

    $sheet->setCellValue("H1", 'likes');

    $sheet->setCellValue("I1", 'saves');

    $sheet->setCellValue("J1", 'is_trending');

    $sheet->setCellValue("K1", 'is_breaking');

    $sheet->setCellValue("L1", 'language');

    $sheet->setCellValue("M1", 'reports');

    $sheet->setCellValue("N1", 'date_created');





    $sql = "SELECT * FROM `articles`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_id']);

            $sheet->setCellValue("C".$cell."", $row['category_name']);

            $sheet->setCellValue("D".$cell."", $row['title']);

            $sheet->setCellValue("E".$cell."", $row['cover_image']);

            $sheet->setCellValue("F".$cell."", $row['description']);

            $sheet->setCellValue("G".$cell."", $row['views']);

            $sheet->setCellValue("H".$cell."", $row['likes']);

            $sheet->setCellValue("I".$cell."", $row['saves']);

            $sheet->setCellValue("J".$cell."", $row['is_trending']);

            $sheet->setCellValue("K".$cell."", $row['is_breaking']);

            $sheet->setCellValue("L".$cell."", $row['language']);

            $sheet->setCellValue("M".$cell."", $row['reports']);

            $sheet->setCellValue("N".$cell."", $row['date_created']);



            $cell++;

        }





    $filename = "articles.xlsx";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");

    $writer->save("php://output");



}







if (isset($_GET['export_video_articles_csv'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_id');

    $sheet->setCellValue("C1", 'category_name');

    $sheet->setCellValue("D1", 'title');

    $sheet->setCellValue("E1", 'thumbnail');

    $sheet->setCellValue("F1", 'video');

    $sheet->setCellValue("G1", 'description');

    $sheet->setCellValue("H1", 'views');

    $sheet->setCellValue("I1", 'likes');

    $sheet->setCellValue("J1", 'saves');

    $sheet->setCellValue("K1", 'language');

    $sheet->setCellValue("L1", 'reports');

    $sheet->setCellValue("M1", 'date_created');





    $sql = "SELECT * FROM `video_articles`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_id']);

            $sheet->setCellValue("C".$cell."", $row['category_name']);

            $sheet->setCellValue("D".$cell."", $row['title']);

            $sheet->setCellValue("E".$cell."", $row['thumbnail']);

            $sheet->setCellValue("F".$cell."", $row['video']);

            $sheet->setCellValue("G".$cell."", $row['description']);

            $sheet->setCellValue("H".$cell."", $row['views']);

            $sheet->setCellValue("I".$cell."", $row['likes']);

            $sheet->setCellValue("J".$cell."", $row['saves']);

            $sheet->setCellValue("K".$cell."", $row['language']);

            $sheet->setCellValue("L".$cell."", $row['reports']);

            $sheet->setCellValue("M".$cell."", $row['date_created']);



            $cell++;

        }





    $filename = "video_articles.csv";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Csv");

    $writer->save("php://output");



}



if (isset($_GET['export_video_articles_excel'])) {



    $spreadsheet = new Spreadsheet();



    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue("A1", 'id');



    $sheet->setCellValue("B1", 'category_id');

    $sheet->setCellValue("C1", 'category_name');

    $sheet->setCellValue("D1", 'title');

    $sheet->setCellValue("E1", 'thumbnail');

    $sheet->setCellValue("F1", 'video');

    $sheet->setCellValue("G1", 'description');

    $sheet->setCellValue("H1", 'views');

    $sheet->setCellValue("I1", 'likes');

    $sheet->setCellValue("J1", 'saves');

    $sheet->setCellValue("K1", 'language');

    $sheet->setCellValue("L1", 'reports');

    $sheet->setCellValue("M1", 'date_created');





    $sql = "SELECT * FROM `video_articles`";



    $query_run = mysqli_query($conn, $sql) or DIE("Error Occured");



        $cell = 2;

        foreach ($query_run as $row) {

            $sheet->setCellValue("A".$cell."", $row['id']);



            $sheet->setCellValue("B".$cell."", $row['category_id']);

            $sheet->setCellValue("C".$cell."", $row['category_name']);

            $sheet->setCellValue("D".$cell."", $row['title']);

            $sheet->setCellValue("E".$cell."", $row['thumbnail']);

            $sheet->setCellValue("F".$cell."", $row['video']);

            $sheet->setCellValue("G".$cell."", $row['description']);

            $sheet->setCellValue("H".$cell."", $row['views']);

            $sheet->setCellValue("I".$cell."", $row['likes']);

            $sheet->setCellValue("J".$cell."", $row['saves']);

            $sheet->setCellValue("K".$cell."", $row['language']);

            $sheet->setCellValue("L".$cell."", $row['reports']);

            $sheet->setCellValue("M".$cell."", $row['date_created']);



            $cell++;

        }





    $filename = "video_articles.xlsx";



    // Redirect output to the admion



    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");

    header("Content-Disposition: attachment;filename=".$filename."");

    header("Cache-Control: max-age=0");



   // If use IE - 9

    header("Cache-Control: max-age=1");



    $writer = IOFactory::createWriter($spreadsheet, "Xlsx");

    $writer->save("php://output");



}
