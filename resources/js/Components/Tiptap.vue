<template>
    <div id="text-editor">
      <div class="toolbar" v-if="editor">
        <div class="align-dropdown">
          <button class="dropbtn">
            Heading ▼
          </button>
          <div class="dropdown-content">
            <a 
              v-for="index in 6"
              :class="{ 'active': editor.isActive('heading', { level: index }) }"
              :style="{ fontSize: (20 - index) + 'px' }"
              @click="onHeadingClick(index)"
              role="button">
              H{{ index }}
            </a>
          </div>
        </div>
  
        <button 
          v-for="{ slug, option, active, icon }, index in textActions"
          :class="{ 'active': editor.isActive(active) }"
          @click="onActionClick(slug, option)">
          <i :class="icon"></i>
        </button>
      </div>
  
      <editor-content :editor="editor" />
  
      <div v-if="editor" class="footer">
        <span class="characters-count" :class="maxLimit ? limitWarning : ''">
          {{ charactersCount }} {{ maxLimit ? `/ ${maxLimit} characters` : 'characters' }}
        </span>
        |
        <span class="words-count">
          {{ wordsCount }} words
        </span>
      </div>
    </div>
  </template>
  
  <script>
  import { Editor, EditorContent } from '@tiptap/vue-3';
  import StarterKit from '@tiptap/starter-kit';
  import TextAlign from '@tiptap/extension-text-align';
  import Underline from '@tiptap/extension-underline';
  import Subscript from '@tiptap/extension-subscript';
  import Superscript from '@tiptap/extension-superscript';
  import CharacterCount from '@tiptap/extension-character-count';

  export default {
    components: {
      EditorContent,
    },
    props: {
      modelValue: {
        type: String,
        default: '',
      },
      maxLimit: {
        type: Number,
        default: null,
      },
    },
    data() {
      return {
        editor: null,
        textActions: [
          { slug: 'bold', icon: 'ri-bold', active: 'bold' },
          { slug: 'italic', icon: 'ri-italic', active: 'italic' },
          { slug: 'underline', icon: 'ri-underline', active: 'underline' },
          { slug: 'strike', icon: 'ri-strikethrough', active: 'strike' },
          { slug: 'align', option: 'left', icon: 'ri-align-left', active: { textAlign: 'left' } },
          { slug: 'align', option: 'center', icon: 'ri-align-center', active: { textAlign: 'center' } },
          { slug: 'align', option: 'right', icon: 'ri-align-right', active: { textAlign: 'right' } },
          { slug: 'align', option: 'justify', icon: 'ri-align-justify', active: { textAlign: 'justify' } },
          { slug: 'bulletList', icon: 'ri-list-unordered', active: 'bulletList' },
          { slug: 'orderedList', icon: 'ri-list-ordered', active: 'orderedList' },
          { slug: 'subscript', icon: 'ri-subscript-2', active: 'subscript' },
          { slug: 'superscript', icon: 'ri-superscript-2', active: 'superscript' },
          { slug: 'undo', icon: 'ri-arrow-go-back-line', active: 'undo' },
          { slug: 'redo', icon: 'ri-arrow-go-forward-line', active: 'redo' },
          { slug: 'clear', icon: 'ri-format-clear', active: 'clear' },
        ],
      }
    },
    computed: {
      charactersCount() {
        return this.editor.storage.characterCount.characters();
      },
      wordsCount() {
        return this.editor.storage.characterCount.words();
      },
      limitWarning() {
        const isCloseToMax = this.charactersCount >= this.maxLimit - 20;
        const isMax = this.charactersCount === this.maxLimit;
        if (isCloseToMax && !isMax) return 'warning';
        if (isMax) return 'danger';
        return '';
      },
    },
    watch: {
      modelValue(value) {
        if (this.editor.getHTML() === value) return;
        this.editor.commands.setContent(this.modelValue, false);
      },
    },
    methods: {
      onActionClick(slug, option = null) {
        const vm = this.editor.chain().focus();
        const actionTriggers = {
          bold: () => vm.toggleBold().run(),
          italic: () => vm.toggleItalic().run(),
          underline: () => vm.toggleUnderline().run(),
          strike: () => vm.toggleStrike().run(),
          bulletList: () => vm.toggleBulletList().run(),
          orderedList: () => vm.toggleOrderedList().run(),
          align: () => vm.setTextAlign(option).run(),
          subscript: () => vm.toggleSubscript().run(),
          superscript: () => vm.toggleSuperscript().run(),
          undo: () => vm.undo().run(),
          redo: () => vm.redo().run(),
          clear: () => { 
            vm.clearNodes().run();
            vm.unsetAllMarks().run();
          },
        };
        
        actionTriggers[slug]();
      },
      onHeadingClick(index) {
        const vm = this.editor.chain().focus();
        vm.toggleHeading({ level: index }).run()
      }
    },
    mounted() {
      this.editor = new Editor({
        content: this.modelValue,
        extensions: [
          StarterKit,
          Underline,
          Subscript,
          Superscript,
          CharacterCount.configure({
            limit: this.maxLimit,
          }),
          TextAlign.configure({
            types: ['heading', 'paragraph'],
          }),
        ],
        onUpdate: () => {
          this.$emit('update:modelValue', this.editor.getHTML());
        },
      });
    },
    beforeUnmount() {
      this.editor.destroy();
    },
  }
</script>
  