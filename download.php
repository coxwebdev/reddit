<?
require_once 'PHPExcel/Classes/PHPExcel.php';

function excelDownloadScrape($scrape_id, $conn = '') {
   $scrape = getScrapeFromDB($scrape_id, $conn);
   $posts = getPostsByScrapeID($scrape['scrape_id'], $conn);

   $objPHPExcel = new PHPExcel();
   $i = 1;
   $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue('A'.$i, 'Subreddit')
               ->setCellValue('B'.$i, 'Post ID')
               ->setCellValue('C'.$i, 'Title')
               ->setCellValue('D'.$i, 'Content')
               ->setCellValue('E'.$i, 'Username')
               ->setCellValue('F'.$i, 'User Karma')
               ->setCellValue('G'.$i, 'User Created')
               ->setCellValue('H'.$i, 'Upvote')
               ->setCellValue('I'.$i, 'Downvote')
               ->setCellValue('J'.$i, 'Total Comments')
               ->setCellValue('K'.$i, 'Top Level Comments')
               ->setCellValue('L'.$i, 'Permalink')
               ->setCellValue('M'.$i, 'Thumbnail')
               ->setCellValue('N'.$i, 'Posted On')
      ;
   $i++;
   foreach ($posts as $post) {
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, $scrape['subreddit'])
                  ->setCellValue('B'.$i, $post['reddit_post_id'])
                  ->setCellValue('C'.$i, $post['title'])
                  ->setCellValue('D'.$i, $post['content'])
                  ->setCellValue('E'.$i, $post['reddit_user'])
                  ->setCellValue('F'.$i, $post['karma'])
                  ->setCellValue('G'.$i, $post['user_created'])
                  ->setCellValue('H'.$i, $post['upvote'])
                  ->setCellValue('I'.$i, $post['downvote'])
                  ->setCellValue('J'.$i, $post['total_comments'])
                  ->setCellValue('K'.$i, $post['top_level_comments'])
                  ->setCellValue('L'.$i, $post['permalink'])
                  ->setCellValue('M'.$i, $post['thumbnail'])
                  ->setCellValue('N'.$i, $post['created'])
         ;
      $i++;
   }
   $objPHPExcel->setActiveSheetIndex(0);
   // Redirect output to a client’s web browser (Excel5)
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment;filename="scrape.xls"');
   header('Cache-Control: max-age=0');
   // If you're serving to IE 9, then the following may be needed
   header('Cache-Control: max-age=1');

   // If you're serving to IE over SSL, then the following may be needed
   header ('Expires: Mon, 26 Jul 1990 05:00:00 GMT'); // Date in the past
   header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
   header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
   header ('Pragma: public'); // HTTP/1.0

   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
   $objWriter->save('php://output');
   exit;

}

function excelDownloadPost($post_id, $conn = '') {
   $post = getPostFromDB($post_id, $conn);
   $comments = getCommentsForPost($post['post_id'], $conn);

   $objPHPExcel = new PHPExcel();
   $i = 1;
   $objPHPExcel->setActiveSheetIndex(0)
               ->setCellValue('A'.$i, 'Post ID')
               ->setCellValue('B'.$i, 'Post Title')
               ->setCellValue('C'.$i, 'Comment')
               ->setCellValue('D'.$i, 'Username')
               ->setCellValue('E'.$i, 'User Karma')
               ->setCellValue('F'.$i, 'User Created')
               ->setCellValue('G'.$i, 'Upvote')
               ->setCellValue('H'.$i, 'Downvote')
               ->setCellValue('I'.$i, 'Replies')
      ;
   $i++;
   foreach ($comments as $comment) {
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue('A'.$i, $post['reddit_post_id'])
                  ->setCellValue('B'.$i, $post['title'])
                  ->setCellValue('C'.$i, html_entity_decode($comment['comment']))
                  ->setCellValue('D'.$i, $comment['reddit_user'])
                  ->setCellValue('E'.$i, $comment['karma'])
                  ->setCellValue('F'.$i, $comment['user_created'])
                  ->setCellValue('G'.$i, $comment['ups'])
                  ->setCellValue('H'.$i, $comment['downs'])
                  ->setCellValue('I'.$i, $comment['replies'])
         ;
      $i++;
   }
   $objPHPExcel->setActiveSheetIndex(0);
   // Redirect output to a client’s web browser (Excel5)
   header('Content-Type: application/vnd.ms-excel');
   header('Content-Disposition: attachment;filename="post.xls"');
   header('Cache-Control: max-age=0');
   // If you're serving to IE 9, then the following may be needed
   header('Cache-Control: max-age=1');

   // If you're serving to IE over SSL, then the following may be needed
   header ('Expires: Mon, 26 Jul 1990 05:00:00 GMT'); // Date in the past
   header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
   header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
   header ('Pragma: public'); // HTTP/1.0

   $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
   $objWriter->save('php://output');
   exit;

}

?>
