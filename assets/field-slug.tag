<field-slug class="field-slug-field">

  <div class="uk-position-relative field-text-container">
      <input ref="input" class="uk-width-1-1" bind="{opts.bind}" value="{value}" type="text" placeholder="{App.i18n.get('leave blank to autogenerate')}" pattern="[a-zA-Z0-9-_\/]+" />
  </div>

  <script>

    var $this = this;

    this.on('mount', function() {
      this.update();
    });

    this.on('bindingupdated', function() {
      $this.$setValue(this.value);
    });

    this.$updateValue = function(value) {
      if (value) {
        this.value = value;
      }
      this.update();
    }.bind(this);

  </script>

</field-slug>
