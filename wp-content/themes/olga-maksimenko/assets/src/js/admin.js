(function ($) {
  'use strict';

  function updateGallery($field) {
    const ids = $field.find('[data-gallery-list] [data-id]').map(function () {
      return $(this).data('id');
    }).get();
    $field.find('[data-gallery-value]').val(ids.join(','));
  }

  $('[data-olga-gallery]').each(function () {
    const $field = $(this);
    const $list = $field.find('[data-gallery-list]');

    $list.sortable({
      items: '> [data-id]',
      tolerance: 'pointer',
      update: function () { updateGallery($field); }
    });

    $field.on('click', '[data-gallery-add]', function () {
      const frame = wp.media({
        title: 'Выберите фотографии проекта',
        button: { text: 'Добавить в галерею' },
        library: { type: 'image' },
        multiple: true
      });

      frame.on('select', function () {
        frame.state().get('selection').each(function (attachment) {
          const image = attachment.toJSON();
          if ($list.find('[data-id="' + image.id + '"]').length) return;

          const source = image.sizes && image.sizes.thumbnail ? image.sizes.thumbnail.url : image.url;
          const $item = $('<li>', { class: 'olga-gallery-item', 'data-id': image.id });
          $('<img>', { src: source, alt: '' }).appendTo($item);
          $('<span>', { class: 'olga-gallery-item__id', text: 'ID ' + image.id }).appendTo($item);
          $('<button>', {
            type: 'button',
            class: 'olga-gallery-remove',
            'aria-label': 'Удалить фотографию из галереи',
            text: '×'
          }).appendTo($item);
          $list.append($item);
        });
        updateGallery($field);
      });

      frame.open();
    });

    $field.on('click', '.olga-gallery-remove', function () {
      $(this).closest('[data-id]').remove();
      updateGallery($field);
    });
  });

  $('[data-home-projects]').each(function () {
    const $root = $(this);
    const $selected = $root.find('[data-selected-projects]');
    const $available = $root.find('[data-available-projects]');
    const max = Number($root.data('max-projects')) || 6;

    function syncProjects() {
      const ids = $selected.children('[data-id]').map(function () { return $(this).data('id'); }).get();
      $root.find('[data-project-selection]').val(ids.join(','));
      $root.find('[data-selected-count]').text(ids.length);
      $root.find('[data-selected-empty]').prop('hidden', ids.length > 0);
      $selected.children().addClass('is-selected').find('[data-project-toggle]').text('Убрать');
      $available.children().removeClass('is-selected').find('[data-project-toggle]').text('Добавить');
    }

    $selected.sortable({
      items: '> [data-id]',
      handle: '.olga-project-choice__handle',
      tolerance: 'pointer',
      update: syncProjects
    });

    $root.on('click', '[data-project-toggle]', function () {
      const $item = $(this).closest('[data-id]');
      if ($item.parent().is($selected)) {
        $item.appendTo($available);
      } else {
        if ($selected.children('[data-id]').length >= max) {
          window.alert('Можно выбрать не больше ' + max + ' проектов.');
          return;
        }
        $item.appendTo($selected);
      }
      syncProjects();
    });

    syncProjects();
  });
})(jQuery);
