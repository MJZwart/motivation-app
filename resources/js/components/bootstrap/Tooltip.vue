<template>
    <div class="tooltip-container">
        <slot />
        <div class="tooltip">
            <span class="text">
                {{text}}
            </span>
        </div>    
    </div>    
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            required: true,
        },
    },
}
</script>

<style lang="scss" scoped>
.tooltip-container { 
  position: relative;
  display: inline-block;
}

.tooltip-container:hover .tooltip{
  opacity: 0.9;

}

.tooltip { 
  color: #ffffff;
  text-align: center;
  padding: 5px 10px;
  border-radius: 4px;

  width: max-content;
  bottom: 100%;
  left:50%; 
  transform:translateX(-50%);

  opacity: 0;
  transition: opacity 0.5s;

  position: absolute;
  z-index: 1;

  background: #000000;
}

.text::after {
  content: " ";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #000000 transparent transparent transparent;

}
</style>