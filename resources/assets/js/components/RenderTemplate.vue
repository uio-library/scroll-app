<script>
    export default {
        functional: true,
        props: {
            template: String,
        },
        render: function (h, context) {
            return h({
                template: `<div class="padded">${context.props.template}</div>`,
            });
        }
    }
</script>
