$(function() {
    $('#vegas').vegas({
        slides: [
            { src: './images/IBUS458A2159_TP_V-compressor.jpg'  },
            { src: './images/IBUS458A1857_TP_V-compressor.jpg  '  },
            { src: './images/IBUS458A2196_TP_V-compressor.jpg'  },
            { src: './images/IBUS458A2341_TP_V-compressor.jpg'  }
        ],
        overlay: './jquery/js/vegas/overlays/08.png', //フォルダ『overlays』の中からオーバーレイのパターン画像を選択
        transition: 'fade2', //スライドを遷移させる際のアニメーション
        transitionDuration: 4000, //スライドの遷移アニメーションの時間
        delay: 10000, //スライド切り替え時の遅延時間
        animation: 'random', //スライド表示中のアニメーション
        animationDuration: 20000, //スライド表示中のアニメーションの時間
    });
});
