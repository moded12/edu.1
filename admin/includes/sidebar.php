<!-- FILE: /admin/includes/sidebar.php -->
<style>
  #sidebarToggle {
    display: none;
    position: fixed;
    top: 10px;
    right: 10px;
    z-index: 1100;
    background-color: #198754;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
  }

  @media (max-width: 768px) {
    #sidebar-wrapper {
      transform: translateX(100%);
      transition: transform 0.3s ease;
    }
    #sidebar-wrapper.active {
      transform: translateX(0);
    }
    #sidebarToggle {
      display: block;
    }
  }
</style>

<button id="sidebarToggle">📋 القائمة</button>

<div class="bg-white border-start position-fixed h-100 shadow" id="sidebar-wrapper" style="width:250px; right:0; top:0; z-index:1050;">
  <div class="p-3 bg-success text-white fw-bold text-center">📋 لوحة التحكم</div>
  <div class="list-group list-group-flush">
    <a href="add_application.php" class="list-group-item list-group-item-action">➕ إضافة تطبيق</a>
    <a href="add_class.php" class="list-group-item list-group-item-action">➕ إضافة صف</a>
    <a href="add_material.php" class="list-group-item list-group-item-action">➕ إضافة مادة</a>
    <a href="add_semester.php" class="list-group-item list-group-item-action">➕ إضافة فصل</a>
    <a href="add_section.php" class="list-group-item list-group-item-action">➕ إضافة قسم</a>
    <a href="add_group.php" class="list-group-item list-group-item-action">➕ إضافة مجموعة</a>
    <a href="add_lessons.php" class="list-group-item list-group-item-action">➕ إضافة درس</a>
    <hr>
    <a href="view_lessons.php" class="list-group-item list-group-item-action">📚 عرض الدروس</a>
    <a href="edit_lesson.php?id=1" class="list-group-item list-group-item-action">✏️ تعديل درس</a>
    <a href="delete_lesson.php?id=1" class="list-group-item list-group-item-action">🗑️ حذف درس</a>
    <a href="move_lesson.php?id=1" class="list-group-item list-group-item-action">🔀 نقل درس</a>
  </div>
</div>

<script>
  const toggleBtn = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar-wrapper");

  toggleBtn.addEventListener("click", function () {
    sidebar.classList.toggle("active");
  });
</script>