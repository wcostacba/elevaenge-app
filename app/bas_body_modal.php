<!-- modal com iframe -->
<div class="modal fade" id="modaliframe1" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Modal</h4>
            </div>

            <div class="modal-body">
                <iframe src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal com iframe (segundo modal over) -->
<div class="modal fade" id="modaliframe2" tabindex="-1" role="dialog" style="z-index: 10000 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Modal</h4>
            </div>

            <div class="modal-body">
                <iframe src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal com iframe (terceiro modal over) -->
<div class="modal fade" id="modaliframe3" tabindex="-1" role="dialog" style="z-index: 100000 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Modal</h4>
            </div>

            <div class="modal-body">
                <iframe src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </div>
</div>

<!-- modal com iframe (quarto modal over) -->
<div class="modal fade" id="modaliframe4" tabindex="-1" role="dialog" style="z-index: 1000000 !important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header label-primary">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Modal</h4>
            </div>

            <div class="modal-body">
                <iframe src="" frameborder="0"></iframe>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("a[data-modaliframe='1'],button[data-modaliframe='1']").click(function(e) {
            e.preventDefault();
            modaliframe($(this));
        });
    });
    
    // base limpa
    var b = false;
    
    function modaliframe(elem) {
        var e = elem;
        var m = $("#modaliframe1");
        //var leg = e.data("loadingleg");

        /* OPCOES
            tam = tamanho do modal - pequeno (modal-sm) ou grande (modal-lg)

            btnok = botão de ação (salvar, confirmar) - false ou string
            btnokclass = class do botao de acao - estilo

            titulo = titulo do modal - string
            pag = pagina a ser aberta no iframe - url                
        */
        
        //loading(l);

        // over modal
        if(typeof e.data("modaliframe") !== "undefined") {
            // segundo
            if(e.data("modaliframe") == "2") {
                m = $("#modaliframe2");
                
            // terceiro
            } else if(e.data("modaliframe") == "3") {
                m = $("#modaliframe3");
                
            // quarto
            } else if(e.data("modaliframe") == "4") {
                m = $("#modaliframe4");
            }
        }
        
        // primeira execucao capta o codigo base
        if(!b) {
            b = m.html();
        }

        // tamanho do modal
        if(typeof e.data("tam") !== "undefined") {
            if(e.data("tam") == "pequeno") {
                $(m).find(".modal-dialog").addClass("modal-sm");

            } else if(e.data("tam") == "grande") {
                $(m).find(".modal-dialog").addClass("modal-lg")
            }
            
        // se nao existe o data de tamanho, deixa o tamanho padrao
        } else {
            $(m).find(".modal-dialog").removeClass("modal-sm");
            $(m).find(".modal-dialog").removeClass("modal-lg");
        }

        // btn acao legenda
        if(typeof e.data("btnok") !== "undefined") {
            if(e.data("btnok")) {
                $(m).find(".btn-success").html(e.data("btnok"));
                
            } else {
                $(m).find(".btn-success").addClass("hidden");
            }
        }

        // btn acao class
        if(typeof e.data("btnokclass") !== "undefined") {
            $(m).find(".btn-success").addClass(e.data("btnokclass"));
        }

        // basico            
        $(m).find(".modal-title").html(e.data("titulo"));
        
        // conteudo modal src
        $(m).find("iframe").attr("src",e.data("pag"));

        // clique acao
        m.find(".btn-success").on("click", function() {
            // acao
            var ifr = m.find("iframe").contents();
            var frm = ifr.find("form");
            var cfrm = frm[0].reportValidity();

            if(cfrm) {
                $(this).attr("disabled", true);
                loading();
                frm.submit();
            }
        });

        // ajuste altura
        m.find("iframe").on("load", function() {
            var i = $(this);

            setTimeout(function() {
                var h = i.contents().find("#conteudo").height();
                if(h == 0) { h = 150; }
                i.css("height", h+"px");
            }, 200);

            loading_fecha();

            // necessaio aqui pq as vezes o formulario é acionado pelo enter do teclado
            $(m.find("iframe").contents().find("form")[0]).on("submit", function() {
                loading();
            });

            // necessario aqui pq dentro do iframe não é chamado o arquivo bas_body_modal.php
            $(m.find("iframe").contents().find(".loading")).on("click", function() {
                loading();
            });
        });

        // padrao abertura
        $(m).modal({
            backdrop: "static"
        });
        
        // fechar
        m.on("hidden.bs.modal", function() {
            m.html(b);
            
            // btn acao legenda
            if(typeof e.data("referreload") !== "undefined") {
                if(e.data("referreload")) {
                    loading();
                    window.location.reload();
                }
            }
        });        
    }
</script>