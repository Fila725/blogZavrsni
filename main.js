const navItems = document.querySelector('.nav__items');
const openNavBtn = document.querySelector('#open__nav-btn');
const closeNavBtn = document.querySelector('#close__nav-btn');

// opens nav menu
const openNav = () => {
  navItems.style.display = 'flex';
  openNavBtn.style.display = 'none';
  closeNavBtn.style.display = 'inline-block';
};

// closes nav dropdown
const closeNav = () => {
  navItems.style.display = 'none';
  openNavBtn.style.display = 'inline-block';
  closeNavBtn.style.display = 'none';
};

openNavBtn.addEventListener('click', openNav);
closeNavBtn.addEventListener('click', closeNav);

/**
 * ------------------------------------------------------------
 * Dashboard "Edit Post" modal with fetch()
 * ------------------------------------------------------------
 */
document.addEventListener('DOMContentLoaded', () => {
  const overlay = document.getElementById('editPostOverlay');
  if (!overlay) return; // not on dashboard page

  const closeBtn = document.getElementById('editPostCloseBtn');
  const cancelBtn = document.getElementById('editPostCancelBtn');
  const form = document.getElementById('editPostForm');

  const errorBox = document.getElementById('editPostError');
  const idInput = document.getElementById('editPostId');
  const titleInput = document.getElementById('editPostTitleInput');
  const excerptInput = document.getElementById('editPostExcerpt');
  const contentInput = document.getElementById('editPostContent');

  let activeRow = null;

  const showError = (msg) => {
    errorBox.textContent = msg;
    errorBox.style.display = 'block';
  };

  const clearError = () => {
    errorBox.textContent = '';
    errorBox.style.display = 'none';
  };

  const openModal = () => {
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden', 'false');
    setTimeout(() => titleInput.focus(), 0);
  };

  const closeModal = () => {
    overlay.style.display = 'none';
    overlay.setAttribute('aria-hidden', 'true');
    clearError();
    form.reset();
    idInput.value = '';
    activeRow = null;
  };

  // Close actions
  closeBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeModal();
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // Click "Edit" (event delegation)
  document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.js-edit-post');
    if (!btn) return;

    clearError();

    const postId = btn.dataset.postId;
    activeRow = btn.closest('tr');

    try {
      const res = await fetch(`dashboard.php?ajax=get_post&id=${encodeURIComponent(postId)}`, {
        headers: { 'Accept': 'application/json' }
      });

      const data = await res.json().catch(() => null);

      if (!res.ok || !data || !data.ok) {
        showError((data && data.error) ? data.error : 'Failed to load post.');
        return;
      }

      idInput.value = data.post.id;
      titleInput.value = data.post.title ?? '';
      excerptInput.value = data.post.excerpt ?? '';
      contentInput.value = data.post.content ?? '';

      openModal();
    } catch (err) {
      showError('Network error. Please try again.');
    }
  });

  // Save changes
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearError();

    const payload = {
      ajax: 'update_post',
      id: idInput.value,
      title: titleInput.value,
      excerpt: excerptInput.value,
      content: contentInput.value
    };

    try {
      const res = await fetch('dashboard.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      const data = await res.json().catch(() => null);

      if (!res.ok || !data || !data.ok) {
        showError((data && data.error) ? data.error : 'Failed to save post.');
        return;
      }

      // Update the title text in the table instantly
      if (activeRow) {
        const titleCell = activeRow.querySelector('.js-post-title');
        if (titleCell) titleCell.textContent = data.post.title;
      }

      closeModal();
    } catch (err) {
      showError('Network error. Please try again.');
    }
  });
});
