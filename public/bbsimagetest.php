<?php
$dbh = new PDO(
    'mysql:host=mysql;dbname=example_db;charset=utf8mb4',
    'root',
    '',
    [
        // MYSQL側で型チェックを行うようにする
        PDO::ATTR_EMULATE_PREPARES   => false,
        // 1ステートメントで複数のSQL文を実行できなくする
        PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
    ]
);
// URL用の「現在ページ」
// PHP_SELF はドキュメントルートからの相対URL（例: /bbsimagetest.php）
$current_url = $_SERVER['PHP_SELF'];  // ← URLパスを使う
// $current_file = __FILE__; // ← これは「ファイルパス」なのでNG

if (isset($_POST['body'])) {
  // POSTで送られてくるフォームパラメータ body がある場合

  $image_filename = null;
  if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
    // アップロードされた画像がある場合
    if (preg_match('/^image\/[a-z0-9]+$/', mime_content_type($_FILES['image']['tmp_name']))) {
      header("HTTP/1.1 302 Found");
      header("Location: $current_file");
    }

    // 元のファイル名から拡張子を取得
    $pathinfo = pathinfo($_FILES['image']['name']);
    $extension = $pathinfo['extension'];
    // 新しいファイル名を決める。他の投稿の画像ファイルと重複しないように時間+乱数で決める。
    $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
    $filepath =  '/var/www/upload/image/' . $image_filename;
    move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
  }

  // insertする
  $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body, image_filename) VALUES (:body, :image_filename)");
  $insert_sth->execute([
    ':body' => $_POST['body'],
    ':image_filename' => $image_filename,
  ]);

  // 処理が終わったらリダイレクトする
  // リダイレクトしないと，リロード時にまた同じ内容でPOSTすることになる
  header("HTTP/1.1 302 Found");
  header("Location: $current_file");
  return;
}

// いままで保存してきたものを取得
$select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
$select_sth->execute();
?>

<head>
  <title>画像投稿できる掲示板</title>
</head>

<!-- フォームのPOST先はこのファイル自身にする -->
<form method="POST" action="<?= htmlspecialchars($current_file) ?>" enctype="multipart/form-data">
  <textarea name="body" required></textarea>
  <div style="margin: 1em 0;">
    <input type="file" id="imageInput" accept="image/*" name="image">
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      const imageInput = document.getElementById('imageInput');
      const MAX_SIZE = 5 * 1024 * 1024; // 5MB
      const MAX_WIDTH = 1920;           // 必要に応じて調整
      const MIN_QUALITY = 0.4;          // 下げ止め
    
      imageInput.addEventListener('change', async function () {
        const file = this.files[0];
        if (!file) return;
    
        // 5MB以下ならそのまま
        if (file.size <= MAX_SIZE) return;
    
        // <img> を作って読み込み（オブジェクトURLは後で解放）
        const url = URL.createObjectURL(file);        // ← createObjectURL
        const img = new Image();
        img.src = url;
        await img.decode();
    
        // キャンバスにリサイズ描画
        const scale = Math.min(1, MAX_WIDTH / img.width);
        const canvas = document.createElement('canvas');
        canvas.width = Math.max(1, Math.round(img.width * scale));
        canvas.height = Math.max(1, Math.round(img.height * scale));
        const ctx = canvas.getContext('2d', { alpha: false });
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    
        // 使い終わったURLは解放（メモリリーク防止）
        URL.revokeObjectURL(url);                     // ← revokeObjectURL
        // 画質を段階的に下げて 5MB 未満になるまで再エンコード
        let q = 0.8;
        let blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', q));
        while (blob && blob.size > MAX_SIZE && q > MIN_QUALITY) {
          q = Math.max(MIN_QUALITY, q - 0.1);
          blob = await new Promise(res => canvas.toBlob(res, 'image/jpeg', q));
        }
    
        if (!blob || blob.size > MAX_SIZE) {
          alert('画像の自動圧縮に失敗しました。別の画像を選択してください。');
          this.value = '';
          return;
        }
    
        const newName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
        const newFile = new File([blob], newName, { type: 'image/jpeg' });
    
        const dt = new DataTransfer();
        dt.items.add(newFile);
        this.files = dt.files;

        alert(`画像をJPEGにして自動圧縮しました (${(newFile.size/1024/1024).toFixed(2)}MB)`);
      });
     });
    </script>
  </div>
  <button type="submit">送信</button>
</form>

<hr>

<?php foreach($select_sth as $entry): ?>
  <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
    <dt>ID</dt>
    <dd><?= $entry['id'] ?></dd>
    <dt>日時</dt>
    <dd><?= $entry['created_at'] ?></dd>
    <dt>内容</dt>
    <dd>
      <?= nl2br(htmlspecialchars($entry['body'])) // 必ず htmlspecialchars() すること ?>
      <?php if(!empty($entry['image_filename'])): // 画像がある場合は img 要素を使って表示 ?>
      <div>
        <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
      </div>
      <?php endif; ?>
    </dd>
  </dl>
<?php endforeach ?>

