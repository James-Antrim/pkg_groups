#Attribute                  Type                            HTML        AKA
#First Names                Names -> Text                   Text        Vorname
#Surnames                   Names -> Text                   Text        Nachname
#THM E-Mail                 E-Mail -> Text                  E-Mail      Email
#Supplement (Pre)           Supplement -> Text              Text        Namenszusatz (vor)
#Supplement (Post)          Supplement -> Text              Text        Namenszusatz (nach)
#Telephone (EU)             Telephone -> Text               Telephone   Telefon
#Fax                        Telephone -> Text               Telephone   Fax
#Address                    Address (aggregate)             *           Anschrift
#->Street                   Street -> Text                  Text        x
#->Extra 1                  Text                            Text        x
#->Extra 2                  Text                            Text        x
#->City                     City -> Text                    Text        x
#->Code                     Code -> Text                    Text        x
#Consultation Hours         Consultation Hours (aggregate)  *           Sprechstunden, Sprechzeiten
#->Hours                    Hours -> Subform                *           x
#->->Days                   Text                            Text        x
#->->Times                  Text                            Text        x
#->Arrangement              Checkbox                        Checkbox    x
#Further Information        Editor                          (Textarea)  Weitere Informationen
#Picture                    Picture -> File                 File        Bild
#Add picture source field
#Website                    URL -> Text                     URL         Homepage, Web
#Room                       Room -> Text                    Text        Raum
#Büro                       Room -> Text                    Text        Büro
#Areas of Expertise         Subform                         *           Fachgebiete, Fachgebiet
#->Area of Expertise        Text                            Text        x
#Additional Links           Linked List -> Subform          *           Weiterführende  Links
#->Link                     URL -> Text                     URL         x
#->Text (DE)                Text                            Text        x
#->Text (EN)                Text                            Text        x
#Positions                  Linked List -> Subform          *           Funktionen, Besondere  Funktion
#(Linked List)
#Additional Profiles        Linked List -> Subform          *           Weitere  Profile
#(Linked List) but with migration to other specific attributes as appropriate
#Research Areas             Linked List -> Subform          *           Forschungsgebiete
#(Linked List)
#Projects                   Editor                          (Textarea)  Projekte
#Courses                    Editor                          (Textarea)  Veranstaltungen
#Courses* (Organizer)       Organizer Content               Select     *Veranstaltungen*
#Subjects* (Organizer)      Organizer Content               Select     *Module*
#Schedule* (Organizer)      Organizer Content               Select     *Stundenplan*
#Room* (Organizer)          Organizer Content               Select     *Room*
#the select is in reference to the presentation mode (text, icon, link, ...)
#Obituary                   Editor                          (Textarea)  Nachruf, Ohne Überschrift
#Kontakt (LSE) migrate to other contact fields as appropriate (Anschrift, Telefon, Büro, ...)
#other Telefone             Telephone -> Text               Telephone   weiteres  Telefon, Telefon_privat, Mobil, Telefon 2
#other Fax                  Telephone -> Text               Telephone   weiteres  Fax, Fax_privat
#other E-Mail               E-Mail -> Text                  E-Mail      Email2, weitere E-mail, E-Mail-2, E-Mail 2, E-Mail 3, E-Mail 4
#other Room                 Room -> Text                    Text        weiterer  Raum, Raum 2
#other Address              Address (aggregate)             *           weiterer Anschrift
#(Address)
#weiterer  Kontakt (LSE) migrate to other address or wherever, as appropriate
#Laboratory                 Room -> Text                    Text        Labor
#>>>>> delete Leerzeile  zwischen  Kontakten
#Birthday                   Date                            Date        Geburtstag
#Fields                     Linked List -> Subform          *           Arbeitsgebiete
#Current                    Editor                          (Textarea)  Aktuell, Aktuelles
#>>>>>delete any entries with years in the past
#About Me                   Editor                          (Textarea)  Zur  Person
#Ansprechpartner delete
#Curriculum Vitae           CV -> Subform                   *           Curriculum Vitae, Lebenslauf
#->From                     Text (Date-ish)                 Text        x
#->To                       Text (Date-ish)                 Text        x
#->Activity (DE)            Text                            Text        x
#->Activity (EN)            Text                            Text        x
#Twitter                    Button -> URL                   URL         Twitter, some hidden in other items
#XING                       Button -> URL                   URL         XING, some hidden in other items
#LinkedIn                   Button -> URL                   URL         LinkedIn, some hidden in other items
#Publications               Linked List -> Subform          *           Publikationen
#>>>>> organize a subform....
#Schriftverzeichnis         Button -> URL                   URL         Publikationen :(
#>>>>> migrate W URLs here





