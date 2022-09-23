const DocMenuConfig: object = [
  {
    pages: [
      {
        heading: "dashboard",
        route: "/dashboard",
        svgIcon: "/media/icons/duotone/Design/PenAndRuller.svg",
        fontIcon: "bi-app-indicator"
      },
      {
        sectionTitle: "administrators",
        route: "/admins",
        svgIcon: "/media/icons/duotone/General/User.svg",
        fontIcon: "bi-person",
        sub: [
          {
            heading: "list",
            route: "/admins/list"
          },
          {
            heading: "create",
            route: "/admins/create"
          }
        ]
      },
      {
        sectionTitle: "users",
        route: "/users",
        svgIcon: "/media/icons/duotone/Communication/Group.svg",
        fontIcon: "bi-person",
        sub: [
          {
            heading: "list",
            route: "/users/list"
          },
          {
            heading: "create",
            route: "/users/create"
          }
        ]
      },
      {
        sectionTitle: "phoneNumbers",
        route: "/phones",
        svgIcon: "/media/icons/duotone/Communication/Adress-book1.svg",
        fontIcon: "bi-person",
        sub: [
          {
            heading: "importExcel",
            route: "/phones/import"
          },
          {
            heading: "list",
            route: "/phones/list"
          }
        ]
      },
      {
        sectionTitle: "tags",
        route: "/tags",
        svgIcon: "/media/icons/duotone/Tools/Tools.svg",
        fontIcon: "bi-person",
        sub: [
          {
            heading: "list",
            route: "/tags/list"
          },
          {
            heading: "create",
            route: "/tags/create"
          }
        ]
      },
      {
        heading: "pickMarkPhones",
        route: "/picking/list",
        svgIcon: "/media/icons/duotone/Interface/Phone.svg",
        fontIcon: "bi-app-indicator"
      },
      {
        heading: "report",
        route: "/report",
        svgIcon: "/media/icons/duotone/Files/File.svg",
        fontIcon: "bi-app-indicator"
      },
    ]
  }
];

export default DocMenuConfig;
