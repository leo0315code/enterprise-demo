// 单图上传组件：选本地图直传 /manage/upload，自动回填 URL 输入框并预览
// 与富文本编辑器共用 UploadController（字段名复用 wangeditor-uploaded-image）

function ipToast(msg, type) {
  type = type || 'error';
  let box = document.getElementById('ip-toast');
  if (!box) {
    box = document.createElement('div');
    box.id = 'ip-toast';
    box.style.cssText =
      'position:fixed;top:20px;right:20px;z-index:9999;max-width:340px;padding:10px 16px;' +
      'border-radius:8px;font-size:14px;color:#fff;box-shadow:0 6px 20px rgba(0,0,0,.15);' +
      'transition:opacity .3s;opacity:0;background:#ef4444;';
    document.body.appendChild(box);
  }
  box.textContent = msg;
  box.style.background =
    type === 'success' ? '#22c55e' : type === 'info' ? '#3b82f6' : '#ef4444';
  box.style.opacity = '1';
  clearTimeout(box._t);
  box._t = setTimeout(function () {
    box.style.opacity = '0';
  }, 2600);
}

function ipUploadFile(file, onOk, onErr) {
  const meta = document.querySelector('meta[name="upload-url"]');
  const csrf = document.querySelector('meta[name="csrf-token"]');
  if (!meta) {
    onErr('未找到上传地址配置');
    return;
  }
  const fd = new FormData();
  fd.append('wangeditor-uploaded-image', file);
  fetch(meta.getAttribute('content'), {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '' },
    body: fd,
    credentials: 'same-origin',
  })
    .then(function (r) {
      return r.json().catch(function () {
        throw new Error('返回非 JSON');
      });
    })
    .then(function (d) {
      if (d && d.errno === 0 && d.data && d.data.url) {
        onOk(d.data.url);
      } else {
        onErr((d && d.message) || '上传失败');
      }
    })
    .catch(function () {
      onErr('上传请求出错');
    });
}

function bindImagePicker(root) {
  const nodes = (root || document).querySelectorAll(
    '.image-picker:not([data-bound])'
  );
  nodes.forEach(function (node) {
    node.setAttribute('data-bound', '1');
    const fileInput = node.querySelector('.ip-file');
    const urlInput = node.querySelector('.ip-url');
    const btn = node.querySelector('.ip-btn');
    const preview = node.querySelector('.ip-preview');

    function showPreview(src) {
      if (src) {
        preview.src = src;
        preview.classList.remove('hidden');
      } else {
        preview.classList.add('hidden');
      }
    }

    showPreview(urlInput.value);
    urlInput.addEventListener('input', function () {
      showPreview(urlInput.value.trim());
    });
    btn.addEventListener('click', function () {
      fileInput.click();
    });
    fileInput.addEventListener('change', function () {
      const file = fileInput.files && fileInput.files[0];
      if (!file) return;
      btn.disabled = true;
      const oldText = btn.textContent;
      btn.textContent = '上传中…';
      ipUploadFile(
        file,
        function (url) {
          urlInput.value = url;
          showPreview(url);
          btn.textContent = oldText;
          btn.disabled = false;
          fileInput.value = '';
          ipToast('图片已上传', 'success');
        },
        function (err) {
          ipToast(err, 'error');
          btn.textContent = oldText;
          btn.disabled = false;
          fileInput.value = '';
        }
      );
    });
  });
}

window.ImagePicker = { refresh: bindImagePicker };
document.addEventListener('DOMContentLoaded', function () {
  bindImagePicker(document);
});
