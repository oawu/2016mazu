//
//  Path.h
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/29.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import <Foundation/Foundation.h>
#import <CoreLocation/CoreLocation.h>

@interface Path : NSObject

@property NSString *id, *lat, *lng, *time;
@property CLLocationCoordinate2D position;

- (Path *) initWithDictionary:(NSDictionary *)dictionary;
@end
