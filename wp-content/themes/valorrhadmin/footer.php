				</div>
	        </main>
	    </div>

	    <script src="<?php bloginfo('template_url'); ?>/js/jquery-4.0.0.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/jquery.inputmask.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/bootstrap.bundle.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/lucide.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/chart.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/jquery.validate.min.js"></script>
	    <script src="<?php bloginfo('template_url'); ?>/js/main.js"></script>

		<script>
		    $(document).ready(function() {
			    // 1. Define a mensagem genérica para todos os campos
			    $.extend($.validator.messages, {
			        required: "Este campo é obrigatório.",
			        email: "Por favor, insira um e-mail válido.",
			        url: "Insira um link válido (Ex: https://...)",
			        date: "Insira uma data válida."
			    });

			    // 2. Inicializa a validação no formulário
			    $("#form-perfil").validate({
			        errorElement: "div",
			        errorPlacement: function(error, element) {
			            error.addClass("invalid-feedback font-weight-bold");
			            element.closest(".col-md-6, .col-md-4, .col-md-3, .col-md-2, .col-12").append(error);
			        },
			        highlight: function(element) {
			            $(element).addClass("is-invalid").removeClass("is-valid");
			        },
			        unhighlight: function(element) {
			            $(element).addClass("is-valid").removeClass("is-invalid");
			        },
			        onfocusout: function(element) {
			            $(element).valid();
			        }
			    });
			});
		</script>

	    <?php wp_footer(); ?>

	</body>
</html>