<?php
$name = $_POST['name'] ?? '';
$name_furigana = $_POST['name-furigana'] ?? '';
$mail = $_POST['mail'] ?? '';
$mail_confirm = $_POST['mail-confirm'] ?? '';
$message = $_POST['message'] ?? '';
$errors = [];
$showConfirm = false;

// バリデーション関数
function validateForm(&$errors, $name, $name_furigana, $mail, $mail_confirm, $message) {
  if ($name === '') $errors[] = "名前は必須です。";
  if ($name_furigana === '') $errors[] = "ふりがなは必須です。";
  if ($mail === '' || !filter_var($mail, FILTER_VALIDATE_EMAIL)) $errors[] = "正しいメールアドレスを入力してください。";
  if ($mail !== $mail_confirm) $errors[] = "メールアドレスが一致しません。";
  if ($message === '') $errors[] = "お問い合わせ内容は必須です。";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["confirm"])) {
    validateForm($errors, $name, $name_furigana, $mail, $mail_confirm, $message);
    if (empty($errors)) {
      $showConfirm = true;
    }
  }

  if (isset($_POST["send"])) {
    validateForm($errors, $name, $name_furigana, $mail, $mail_confirm, $message);
    if (empty($errors)) {
      mb_language("Japanese");
      mb_internal_encoding("UTF-8");

      $to = $mail;
      $subject = "【喫茶あこがれ】お問い合わせありがとうございます";
      $headers = "From: cafe@example.com";
      $message = <<<EOM
                  {$name} 様

                  このたびはお問い合わせいただき、誠にありがとうございます。
                  以下の内容でお問い合わせを承りました。

                  ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝
                  【お名前】{$name}
                  【ふりがな】{$name_furigana}
                  【メールアドレス】{$mail}
                  【お問い合わせ内容】
                  {$message}
                  ＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝

                  担当者より、改めてご連絡差し上げます。

                  喫茶あこがれ
                  EOM;

      mb_send_mail($to, $subject, $message, $headers);

      header("Location: thanks.php");
      exit;
    }
  }
}
?>

<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>お問い合わせ | 喫茶あこがれ</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js" defer></script>
  </head>
  <body>
    <header class="l_header">
      <!-- ヘッダーロゴ -->
      <div class = "l_header-logo">
        <a href="index.html" class="l_header-logo_link">
          <img src="img\コーヒーショップ.png" alt="ロゴ" class = "logo-mark">
          <img src="img\喫茶あこがれ.png" alt="ロゴ" class = "logo-title">
        </a>
      </div>
    
      <!-- ハンバーガーメニュー部分 -->
      <div class="l_header-nav">
    
        <!-- ハンバーガーアイコン -->
        <div class="m_hamburger">
          <span class="m-hamburger-bar"></span>
        </div>
    
        <!-- オーバーレイ -->
        <div class="hamburger-overlay"></div>
        <!-- メニュー -->
        <nav class="l_header-nav_content">
          <ul class="l_header-nav_list">
            <li class="l_header-nav_item">
              <a href="index.html" class="l_header-nav_link">TOP</a>
            </li>
            <li class="l_header-nav_item">
              <a href="menu.html" class="l_header-nav_link">MENU</a>
            </li>
            <li class="l_header-nav_item">
              <a href="index.html#access" class="l_header-nav_link">ACCESS</a>
            </li>
            <li class="l_header-nav_item">
              <a href="contactform.php" class="l_header-nav_link">CONTACT</a>
            </li>
            <li class="l_header-nav_item">
              <a href="notFound.html" class="l_header-nav_link">
                <img src="img/Instagram.png" alt="Instagram">
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </header>
    <main>
      <h1 class="page_name">CONTACT</h1>
      <h2 class="page_sub-name">お問い合わせフォーム</h2>

      <?php if ($_SERVER["REQUEST_METHOD"] === "POST" && empty($errors)): ?>
        <!-- 確認画面 -->
        <div class="contact-form">
          <form method="post" action="contactform.php">
            <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
            <input type="hidden" name="name-furigana" value="<?= htmlspecialchars($name_furigana) ?>">
            <input type="hidden" name="mail" value="<?= htmlspecialchars($mail) ?>">
            <input type="hidden" name="mail-confirm" value="<?= htmlspecialchars($mail_confirm) ?>">
            <input type="hidden" name="message" value="<?= htmlspecialchars($message) ?>">

            <div class="form-group">
              <label for="name">名前<span class="required">必須</span></label>
              <p class="input-text"><?= htmlspecialchars($name) ?></p>
            </div>
            <div class="form-group">
              <label for="name-furigana">名前 (ふりがな)<span class="required">必須</span></label>
              <p class="input-text"><?= htmlspecialchars($name_furigana) ?></p>
            </div>
            <div class="form-group">
              <label for="mail">メールアドレス<span class="required">必須</span></label>
              <p class="input-text"><?= htmlspecialchars($mail) ?></p>
            </div>
            <div class="form-group">
              <label for="mail-confirm">メールアドレス (確認)<span class="required">必須</span></label>
              <p class="input-text"><?= htmlspecialchars($mail_confirm) ?></p>
            </div>
            <div class="form-group">
              <label for="message">お問い合わせ内容<span class="required">必須</span></label>
              <p class="input-text"><?= nl2br(htmlspecialchars($message)) ?></p>
            </div>

            <div class="privacyPolicy-check">
              <span class="privacyPolicy-link">プライバシーポリシー</span>に同意の上、送信します。
            </div>
            <div class="privacyPolicy-modal">
              <div class="policy">
                <span class="close-button">&times;</span>
                <div class="privacyPolicy-title">プライバシーポリシー</div>
                喫茶おもかげ（以下、「当店」といいます。）は、本ウェブサイト上で提供するサービスにおける個人情報保護の重要性について認識し、個人情報の保護に関する法律を遵守すると共に、以下のプライバシーポリシー（以下、「本ポリシー」といいます。）を定めます。

                <br><br><br>
                <p class="privacyPolicy-section">第1条（個人情報の定義）</p><br>
                「個人情報」とは、個人情報保護法にいう「個人情報」を指すものとし、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日、住所、電話番号、連絡先その他の記述等により特定の個人を識別できる情報及び容貌、指紋、声紋にかかるデータ、及び健康保険証の保険者番号などの当該情報単体から特定の個人を識別できる情報（個人識別情報）を指します。

                <br><br><br>
                <p class="privacyPolicy-section">第2条（事業者情報）</p><br>
                法人名：喫茶おもかげ<br>
                住所：東京都渋谷区神宮前3丁目1-25<br>
                代表者：佐藤　太郎

                <br><br><br>
                <p class="privacyPolicy-section">第3条（個人情報の取得方法）</p><br>
                当店は、お客さまが利用登録をする際に氏名、生年月日、住所、電話番号、メールアドレス、銀行口座番号、クレジットカード番号、運転免許証番号などの個人情報をお尋ねすることがあります。<br>
                また、お客さまと提携先などとの間でなされたお客さまの個人情報を含む取引記録や決済に関する情報を、当店の提携先（情報提供元、広告主、広告配信先などを含みます。以下、｢提携先｣といいます。）などから収集することがあります。

                <br><br><br>
                <p class="privacyPolicy-section">第4条（個人情報の利用目的）</p><br>
                当店が個人情報を利用する目的は、以下のとおりです。<br><br>
                1.当店サービスの提供・運営のため<br>
                2.お客さまからのお問い合わせに回答するため（本人確認を行うことを含む）<br>
                3.お客さまが利用中のサービスの新機能、更新情報、キャンペーン等及び当店が提供する他サービスの案内のメールを送付するため<br>
                4.メンテナンス、重要なお知らせなど必要に応じたご連絡のため<br>
                5.利用規約に違反したユーザーや、不正・不当な目的でサービスを利用しようとするユーザーの特定をし、ご利用をお断りするため<br>
                6.お客さまにご自身の登録情報の閲覧や変更、削除、ご利用状況の閲覧を行っていただくため<br>
                7.有料サービスにおいて、お客さまに利用料金を請求するため<br>
                8.上記の利用目的に付随する目的

                <br><br><br>
                <p class="privacyPolicy-section">第5条（利用目的の変更）</p><br>
                個人情報の利用目的は、利用目的が変更前と関連性を有すると合理的に認められる場合に限って、個人情報の利用目的を変更するものとします。<br>
                利用目的の変更を行った場合には、変更後の目的について、当店所定の方法により、お客さまに通知し、または本ウェブサイト上に公表するものとします。

                <br><br><br>
                <p class="privacyPolicy-section">第6条（個人データの安全対策について）</p><br>
                当店は、個人情報を保護するため、情報セキュリティに関する規程に基づき、当該個人情報の管理、個人情報の持ち出し方法の指定、第三者からの不正アクセスの防止等の対策を行い、個人情報の漏洩、紛失、改ざん、破壊等の予防を図ります。

                <br><br><br>
                <p class="privacyPolicy-section">第7条（個人データの第三者提供）</p><br>
                1.当店は、次に掲げる場合を除いて、あらかじめお客さまの同意を得ることなく、第三者に個人情報を提供することはありません。ただし、個人情報保護法その他の法令で認められる場合を除きます。<br><br>
                1-1.人の生命、身体または財産の保護のために必要がある場合であって、本人の同意を得ることが困難であるとき<br>
                1-2.公衆衛生の向上または児童の健全な育成の推進のために特に必要がある場合であって、本人の同意を得ることが困難であるとき<br>
                1-3.国の機関もしくは地方公共団体またはその委託を受けた者が法令の定める事務を遂行することに対して協力する必要がある場合であって、本人の同意を得ることにより当該事務の遂行に支障を及ぼすおそれがあるとき<br>
                1-4.あらかじめ次の事項を告知あるいは公表し、かつ当店が個人情報保護委員会に届出をしたとき<br>
                ・利用目的に第三者への提供を含むこと<br>
                ・第三者に提供されるデータの項目<br>
                ・第三者への提供の手段または方法<br>
                ・本人の求めに応じて個人情報の第三者への提供を停止すること<br>
                ・本人の求めを受け付ける方法<br><br>
                2.前項の定めにかかわらず、次に掲げる場合には、当該情報の提供先は第三者に該当しないものとします。<br><br>
                2-1.当店が利用目的の達成に必要な範囲内において個人情報の取扱いの全部または一部を委託する場合<br>
                2-2.合併その他の事由による事業の承継に伴って個人情報が提供される場合<br>
                2-3.個人情報を特定の者との間で共同して利用する場合であって、その旨並びに共同して利用される個人情報の項目、共同して利用する者の範囲、利用する者の利用目的及び当該個人情報の管理について責任を有する者の氏名または名称について、あらかじめ本人に通知し、または本人が容易に知り得る状態に置いた場合

                <br><br><br>
                <p class="privacyPolicy-section">第8条（個人データの手続）</p><br>
                当店は、お客さまが個人情報または第三者提供記録の開示を求める場合、次の場合を除き開示を請求することができます。<br><br>
                1.開示することで本人または第三者の生命、身体、財産その他の権利利益を害するおそれがある場合<br>
                2.開示することで当店の業務の適正な実施に著しい支障を及ぼすおそれがある場合<br>
                3.開示することが法令に違反することとなる場合<br>
                4.開示の請求がご本人からであることが確認できない場合<br>

                <br><br><br>
                <p class="privacyPolicy-section">第9条（個人情報の利用停止等）</p><br>
                当店は、お客さまから個人情報が利用目的の範囲を超えて取り扱われているという理由、または不正の手段により取得されたものであるという理由により、その利用の停止または消去（以下、「利用停止等」といいます。）を求められた場合には、遅滞なく必要な調査を行います。<br>
                調査結果に基づき、その請求に応じる必要があると判断した場合には、遅滞なく、当該個人情報の利用停止等を行います。<br>
                当店は、利用停止等を行った場合、または利用停止等を行わない旨の決定をしたときは、遅滞なく、これをお客さまに通知します。<br>
                利用停止等に多額の費用を有する場合その他利用停止等を行うことが困難な場合であって、お客さまの権利利益を保護するために必要なこれに代わるべき措置をとれる場合は、この代替策を講じるものとします。

                <br><br><br>
                <p class="privacyPolicy-section">第10条（個人情報取扱いに関する相談や苦情の連絡先）</p><br>
                当店の個人情報の取扱いに関するご質問やご不明点、苦情、その他のお問い合わせは、下記までお願いいたします。<br><br>
                住所：東京都渋谷区神宮前3丁目1-25<br>
                社名：合同会社 喫茶おもかげ<br>
                Eメールアドレス：hoge@cafe.jp<br>
                2025年5月21日改定
              </div>
            </div>

            <button type="submit" name="send" value="true" class="submit_button">送信する</button>
            <button type="button" class="return-button" onclick="history.back()">戻る</button>
          </form>
        </div>
        <span class="end-bar"></span>
      <?php else: ?>

        <?php if (!empty($errors)): ?>
          <ul class="error-list">
            <?php foreach ($errors as $error): ?>
              <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <div class="contact-form">
          <form method="post" action="contactform.php">
            <div class="form-group">
              <label for="name">名前<span class="required">必須</span></label>
              <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="山田太郎" required>
            </div>
            <div class="form-group">
              <label for="name-furigana">名前 (ふりがな)<span class="required">必須</span></label>
              <input type="text" id="name-furigana" name="name-furigana" value="<?= htmlspecialchars($name_furigana) ?>" placeholder="やまだたろう" required>
            </div>
            <div class="form-group">
              <label for="mail">メールアドレス<span class="required">必須</span></label>
              <input type="email" id="mail" name="mail" value="<?= htmlspecialchars($mail) ?>" placeholder="xxx@example.com" required>
            </div>
            <div class="form-group">
              <label for="mail-confirm">メールアドレス (確認)<span class="required">必須</span></label>
              <input type="email" id="mail-confirm" name="mail-confirm" value="<?= htmlspecialchars($mail_confirm) ?>" placeholder="xxx@example.com" required>
            </div>
            <div class="form-group">
              <label for="message">お問い合わせ内容<span class="required">必須</span></label>
              <textarea id="message" name="message" rows="6" placeholder="お問い合わせ内容を記載してください。" required><?= htmlspecialchars($message) ?></textarea>
            </div>
            <button type="submit" name="confirm" value="true" class="submit_button">入力内容を確認</button>
          </form>
        </div>
        <span class="end-bar"></span>
      <?php endif; ?>      
    </main>

    <footer class="l_footer">
      <p> copyright &copy; 2025. All rights reserved.</p>
    </footer>

  </body>
</html>
