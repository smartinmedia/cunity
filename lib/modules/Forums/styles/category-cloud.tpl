<script id="category-cloud" type="text/html">
    <section title="{-"Categories"|translate}" style="min-height: 500px;">
        <h3><i class="fa fa-cloud fa-fw"></i>&nbsp;{-"Categories"|translate}</h3>

        <div class="category-cloud">{%#o.cloud%}</div>
    </section>
</script>
<script id="category-cloud-item" type="text/html">
    <a class="label label-primary category-cloud-item"
       href="{%=convertUrl({'module':'forums','action':'category','x':o.tag})%}">{%=o.name%}&nbsp;({%=o.threadCount%}
        )</a>
</script>