<template>
  <div class="col-lg-12 col-md-12 col-sm-12 mb-3  p-0">
    <label><strong>{{ labelTag }}</strong></label>
    <input
      :name="idTag"
      class="form-control tagify_convert"
      :ref="idTag"
      value=""
      disabled
      @change="TagifyInput()"
    />
  </div>
</template>
<style lang="scss">
	@import "@/assets/libs/tagify/tagify.scss";
</style>
<script>
  // import Tagify from '@yaireo/tagify';
  export default {
    name: "UserTag",
    props: ['accounts','labelTag','idTag'],
    data() {
      return {
        TagifyUserList:''
      }
    },
    mounted() {
        this.bankCreateSelect();// sirve para el breadcums console.log(this.$route.path.split('/'))
    },
    methods: {
      TagifyInput() {
        setTimeout(() => {
          let valueInArray = {}
          try {
            valueInArray = JSON.parse(this.$refs[this.idTag].value)
          } catch (error) {
            // console.log(error)
          }
          this.$emit("change",valueInArray);
        }, 300);

      },
      tagTemplate(tagData) {
        this.$refs[this.idTag].value = tagData.value;
        return `
                    <tag title="${tagData.username}"
                      contenteditable='false'
                      spellcheck='false'
                      tabIndex="-1"
                      class="tagify__tag m-0"
                      value="${tagData.value}"
                      name="${tagData.username}"
                      email="${tagData.email}"
                      
                    >
                      <x title='' class='tagify__tag__removeBtn' role='button' aria-label='remove tag'></x>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-1">
                          <div  class="avatar avatar-online">
                            ${ tagData.username.charAt(0) }
                          </div>
                        </div>
                        <span class='tagify__tag-text'>${tagData.username} | ${tagData.email}</span>
                      </div>
                    </tag>
                  `;
      },

      suggestionItemTemplate(tagData) {
        setTimeout(() => {
          if (document.querySelectorAll('.tagify__dropdown__footer').length > 0) {
            
            document.querySelectorAll('.tagify__dropdown__footer')[0].textContent = document.querySelectorAll('.tagify__tag-text').length > 0 ?
              'Solo puedes selecionar un usuario.':
              `${this.accounts.length - 10} usuarios más, usa el buscador.`
          }
        }, 300);
        if (this.$refs[this.idTag].value.length < 1) {
          return `
              <div 
                value="${tagData.value}" name="${tagData.username}"   email="${ tagData.email }" mappedvalue="${tagData.mappedValue}"
                class='tagify__dropdown__item align-items-center ${tagData.class ? tagData.class : ''}'
                tabindex="0"
                role="option"
              >
              <div class="avatar me-1">
                <div  class="avatar avatar-online">
                  ${ tagData.username.charAt(0) }
                </div>
              </div>
            
                <strong>${tagData.username} | ${tagData.email}</strong>
              </div>`;
        }
      },
      bankCreateSelect() {
        const TagifyUserListEl = this.$refs[this.idTag];
        setTimeout(() => {
          this.$refs[this.idTag].removeAttribute('disabled')

          this.TagifyUserList = new Tagify(TagifyUserListEl, {
            texts: {
              notAllowed: "not allowed"
            },
            tagTextProp: 'name', // very important since a custom template is used with this property as text. allows typing a "value" or a "name" to match input with whitelist
            enforceWhitelist: true,
            maxTags: 1,
            editTags: false,
            skipInvalid: false, // do not remporarily add invalid tags
            dropdown: {
              closeOnSelect: true,
              enabled: 0,
              classname: 'users-list',
              searchKeys: ['username', 'email'] // very important to set by which keys to search for suggesttions when typing
            },
            templates: {
              tag: this.tagTemplate,
              dropdownItem: this.suggestionItemTemplate
            },
            whitelist: this.accounts
          });
          
        }, 200);
      },
    }
  };
</script>