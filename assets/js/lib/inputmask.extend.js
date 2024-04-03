Inputmask.extendDefinitions({
  'A': {
    validator: "[A-Za-zA-ZÀ-ÿ\u00f1\u00d1]",
    casing: "upper"
  },
  '+': {
    validator: "[0-9A-Za-zÀ-ÿ\u0410-\u044F\u0401\u0451\u00C0-\u00FF\u00B5]",
    casing: "upper"
  },
  'a': {
    validator: "[ A-Za-zA-ZÀ-ÿ\u00f1\u00d1]",
  },
  'x': {
    validator: "[ a-zA-ZÀ-ÿ0-9#/,.-]",
  },
  'V': {
    validator: "[JEVjev]",
    casing: "upper"
  }
});

Inputmask.extendAliases({
  'nombre': {
    mask: "Aa{3,20}",
    placeholder: " ",
  },
  'rif': {
    mask: "J-9{7,10}",
    placeholder: " ",
  },
  'direccion': {
    mask: "Ax{7,200}",
    placeholder: " ",
  },
  'cedula': {
    mask: "V-9{7,10}",
    placeholder: "V-12345789"
  },
  'rif': {
    mask: "J-9{7,10}",
    placeholder: " "
  },
  'cantidad': {
    mask: "9{1,10}",
    placeholder: " "
  },
  'fecha': {
    alias: "datetime",
    placeholder: "dd/mm/aaaa",
    inputFormat: "dd/mm/yyyy"
  }
});