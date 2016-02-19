{literal}
  <script type="text/javascript">
    cj('#custom_68_election_2016').change(function () {
    if (cj(this).prop('checked')) {
        cj('#custom_68_money_in_politics, #custom_68_voting_rights').prop('checked', true);
    } else {
        cj('#custom_68_money_in_politics, #custom_68_voting_rights').prop('checked', false);
    }
    });
    cj('#custom_68_election_2016').trigger('change');
  </script>
{/literal}
