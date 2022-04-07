<template>
    <section class="dropDownMenuWrapper">
        <button ref="menu" class="dropDownMenuButton" @click="openClose">
            {{menuTitle}}
        </button>

        <div class="iconWrapper" :class="color">
            <div class="bar1" :class="{'bar1--open': isOpen}" />
            <div class="bar2" :class="{'bar2--open': isOpen}" />
            <div class="bar3" :class="{'bar3--open': isOpen}" />
        </div>

        <section v-if="isOpen" class="dropdownMenu">
            <div class="menuArrow" />
            <slot />
        </section>
    </section>
</template>

<script>
export default {
    props: {
        menuTitle: {
            type: String,
            required: false,
        },
        color: {
            type: String,
            required: false,
        },
    },
    data() {
        return {
            isOpen: false,
        }
    },
    methods: {
        openClose() { 
            var _this = this
            const closeListerner = e => {
                if (this.catchOutsideClick(e, _this.$refs.menu ))
                    window.removeEventListener('click', closeListerner) , _this.isOpen = false
            }

            window.addEventListener('click', closeListerner)
            this.isOpen = !this.isOpen;
        },
        catchOutsideClick(event, dropdown)  {
            // When user clicks menu — do nothing
            if (dropdown == event.target)
                return false

            // When user clicks outside of the menu — close the menu
            if (this.isOpen && dropdown != event.target)
                return true
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../../assets/scss/variables';
.dropDownMenuWrapper {
  position: relative;
  width: 40px;
  height: 35px;
  border-radius: 8px;
  -webkit-tap-highlight-color: rgba(0, 0, 0, 0);

  * {
    box-sizing: border-box;
    text-align: left;
  }

  .dropDownMenuButton {
    border: none;
    font-size: inherit;
    background: none;
    outline: none;
    border-radius: 4px;
    position: absolute;
    top: 0;
    display: flex;
    align-items: center;
    padding: 0 20px 0 20px;
    margin: 0;
    line-height: 1;
    width: 100%;
    height: 100%;
    z-index: 2;
    cursor: pointer;
    color: $primary;
  }

  .dropDownMenuButton--dark {
    color: #eee;
  }
  .iconWrapper.white .bar1, .iconWrapper.white .bar2, .iconWrapper.white .bar3 {
    background: rgba(255, 255, 255, 0.5);
  }
  .iconWrapper.white .bar1--open, .iconWrapper.white .bar3--open {
    background: $red;
  }

  .iconWrapper {
    width: 20px;
    height: 25px;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translate(0,-50%);
    z-index: 1;

    .bar1 {
      width: 100%;
      max-width: 28px;
      height: 3px;
      background: $primary;
      position: absolute;
      top: 50%;
      left: 50%;
      border-radius: 9999px;
      transform: translate(-50%, calc(-50% - 8px) );
      transition: all 0.2s ease;
    }

    .bar1--dark {
      background: #eee;
    }

    .bar1--open {
      transform: translate(-50%, -50%) rotate(45deg);
      margin-top: 0;
      background: $red;
    }

    .bar2 {
      width: 100%;
      max-width: 28px;
      height: 3px;
      background: $primary;
      position: absolute;
      top: 50%;
      left: 50%;
      border-radius: 9999px;
      opacity: 1;
      transform: translate(-50%, -50%);
      transition: all 0.2s ease;
    }

    .bar2--dark {
      background: #eee;
    }

    .bar2--open {
      opacity: 0;
    }

    .bar3 {
      width: 100%;
      max-width: 28px;
      height: 3px;
      background: $primary;
      position: absolute;
      top: 50%;
      left: 50%;
      border-radius: 9999px;
      transform: translate(-50%, calc(-50% + 8px) );
      transition: all 0.2s ease;
    }

    .bar3--dark {
      background: #eee;
    }

    .bar3--open {
      top: 50%;
      transform: translate(-50%, -50% ) rotate(-45deg);
      background: $red;
    }

  }

  .iconWrapper--noTitle {
    left: 0;
    top: 0;
    bottom: 0;
    right: 0;
    width: auto;
    height: auto;
    transform: none;
  }

  .dropdownMenu {
    position: absolute;
    top: 100%;
    right:0%;
    width: 100%;
    min-width: max-content;
    min-height: 10px;
    border-radius: 8px;
    border: 1px solid #eee;
    box-shadow: 4px 2px 0 0 rgba(black,.03);
    background: white;
    padding: 5px 10px;
    animation: menu 0.3s ease forwards;

    .menuArrow {
      width: 20px;
      height: 20px;
      position: absolute;
      top: -10px;
      right: 10%;
      border-left: 1px solid #eee;
      border-top: 1px solid #eee;
      background: white;
      transform: rotate(45deg);
      border-radius: 4px 0 0 0;
    }

    .menuArrow--dark {
      background: #333;
      border: none;
    }

    .option {
      width: 100%;
      border-bottom: 1px solid #eee;
      padding: 4px 0;
      cursor: pointer;
      position: relative;
      z-index: 2;

      &:last-child {
        border-bottom: 0;
      }

      * {
        color: inherit;
        text-decoration: none;
        background: none;
        border: 0;
        padding: 0;
        outline: none;
        cursor: pointer;
      }

    }

    .desc {
      opacity: 0.5;
      display: block;
      width: 100%;
      font-size: 14px;
      margin: 3px 0 0 0;
      cursor: default;
    }

  }

  .dropdownMenu--dark {
    background: #333;
    border: none;

    .option {
      border-bottom: 1px solid #888;
    }

    * {
      color: #eee;
    }

  }

  @keyframes menu {
    from { transform: translate3d( 0, 30px ,0 ) }
    to { transform: translate3d( 0, 20px ,0 ) }
  }

}

.dropDownMenuWrapper--noTitle {
  padding: 0;
  width: 60px;
  height: 60px;
}

.dropDownMenuWrapper--dark {
  background: #333;
  border: none;
}
</style>