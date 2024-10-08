Syntax highlight test

```js
import hljs from 'highlight.js/lib/core';
hljs.highlightAll();
```

```ts
interface User {
  name: string;
  id: number;
}
 
class UserAccount {
  name: string;
  id: number;
 
  constructor(name: string, id: number) {
    this.name = name;
    this.id = id;
  }
}
 
const user: User = new UserAccount("Murphy", 1);
```

```json
{ 
	"foo": "bar", 
	"baz": [1, 2, 3] 
}
```

```php
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
```

```ini
; test
foo=bar
```

```xml
<foo>
	<bar>Hello</bar>
</foo>
```

```sh
./foo --bar
```

```css
foo {
	height: 25px;
}
```

```sql
SELECT * FROM foobar WHERE baz = 1;
```

```groovy
class AGroovyBean {
  String color
}

def myGroovyBean = new AGroovyBean()

myGroovyBean.setColor('baby blue')
assert myGroovyBean.getColor() == 'baby blue'

myGroovyBean.color = 'pewter'
assert myGroovyBean.color == 'pewter'
```

```dns
$ORIGIN example.com.
			@                    3600 SOA   ns1.p30.oraclecloud.net. (
			zone-admin.dyndns.com.     ; address of responsible party
			2016072701                 ; serial number
			3600                       ; refresh period
			600                        ; retry period
			604800                     ; expire time
			1800                     ) ; minimum ttl
			86400 NS    ns1.p68.dns.oraclecloud.net.
			86400 NS    ns2.p68.dns.oraclecloud.net.
			86400 NS    ns3.p68.dns.oraclecloud.net.
			86400 NS    ns4.p68.dns.oraclecloud.net.
			3600 MX    10 mail.example.com.
			3600 MX    20 vpn.example.com.
			3600 MX    30 mail.example.com.
			60 A     204.13.248.106
			3600 TXT   "v=spf1 includespf.oraclecloud.net ~all"
			mail                  14400 A     204.13.248.106
			vpn                      60 A     216.146.45.240
			webapp                   60 A     216.146.46.10
			webapp                   60 A     216.146.46.11
		www                   43200 CNAME example.com.
```
